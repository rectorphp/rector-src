<?php

declare(strict_types=1);

namespace Rector\Parallel\Application;

use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use Nette\Utils\FileSystem;
use Nette\Utils\Random;
use PHPStan\Turbo\TurboExtensionEnabler;
use PHPStan\Turbo\TurboExtensionSelector;
use React\EventLoop\StreamSelectLoop;
use React\Socket\ConnectionInterface;
use React\Socket\TcpServer;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\Console\Command\ProcessCommand;
use Rector\Console\Command\WorkerCommand;
use Rector\Parallel\Command\WorkerCommandLineFactory;
use Rector\Parallel\Enum\Action;
use Rector\Parallel\Enum\Content;
use Rector\Parallel\Enum\ReactCommand;
use Rector\Parallel\Enum\ReactEvent;
use Rector\Parallel\Enum\StreamFormat;
use Rector\Parallel\ValueObject\Bridge;
use Rector\Parallel\ValueObject\ParallelProcess;
use Rector\Parallel\ValueObject\ProcessPool;
use Rector\Parallel\ValueObject\Schedule;
use Rector\Testing\PHPUnit\StaticPHPUnitEnvironment;
use Rector\ValueObject\Error\SystemError;
use Rector\ValueObject\ProcessResult;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;

/**
 * Workers process their own balanced bucket first, and are replaced by a fresh one bound to the same bucket
 * after a few chunks to free memory. Idle workers take pending chunks from the largest remaining queue.
 * Each chunk advances progress and re-arms the per-job timeout.
 *
 * Where possible a worker is forked from the main process, so it starts with the container the main process
 * already booted; otherwise it is spawned as a new process that boots on its own.
 */
final class ParallelFileProcessor
{
    private const int SYSTEM_ERROR_LIMIT = 50;

    /**
     * The number of chunks a worker can process before getting killed.
     * In contrast the jobSize defines the maximum size of a chunk, a worker process at a time.
     */
    private const int MAX_CHUNKS_PER_WORKER = 8;

    private ProcessPool $processPool;

    /**
     * @var callable(): WorkerCommand
     */
    private $workerCommandResolver;

    /**
     * @param callable(): WorkerCommand $workerCommandResolver Lazy, as the worker command itself depends on this class
     */
    public function __construct(
        private readonly WorkerCommandLineFactory $workerCommandLineFactory,
        callable $workerCommandResolver,
    ) {
        $this->workerCommandResolver = $workerCommandResolver;
    }

    /**
     * @param callable(int $stepCount): void $postFileCallback Used for progress bar jump
     */
    public function process(
        Schedule $schedule,
        string $mainScript,
        callable $postFileCallback,
        InputInterface $input
    ): ProcessResult {
        // reversed, so that array_pop() hands out the jobs in bucket order
        $jobsPerWorker = array_map(
            array_reverse(...),
            $schedule->getJobsPerWorker()
        );

        /** @var array<string, int> $bucketKeyByIdentifier */
        $bucketKeyByIdentifier = [];

        $streamSelectLoop = new StreamSelectLoop();

        $parallelResultCollector = new ParallelResultCollector();

        $tcpServer = new TcpServer('127.0.0.1:0', $streamSelectLoop);
        $this->processPool = new ProcessPool($tcpServer);

        $tcpServer->on(ReactEvent::CONNECTION, function (ConnectionInterface $connection) use (
            &$jobsPerWorker,
            &$bucketKeyByIdentifier
        ): void {
            $inDecoder = new Decoder($connection, true, StreamFormat::DEPTH, 0, StreamFormat::MAX_LENGTH);
            $outEncoder = new Encoder($connection);

            $inDecoder->on(ReactEvent::DATA, function (array $data) use (
                &$jobsPerWorker,
                &$bucketKeyByIdentifier,
                $inDecoder,
                $outEncoder
            ): void {
                $action = $data[ReactCommand::ACTION];
                if ($action !== Action::HELLO) {
                    return;
                }

                $processIdentifier = $data[Option::PARALLEL_IDENTIFIER];
                $parallelProcess = $this->processPool->getProcess($processIdentifier);
                $parallelProcess->bindConnection($inDecoder, $outEncoder);

                $jobsChunk = $this->popJobChunk($processIdentifier, $jobsPerWorker, $bucketKeyByIdentifier);
                if ($jobsChunk === null) {
                    $this->processPool->quitProcess($processIdentifier);
                    return;
                }

                $parallelProcess->request([
                    ReactCommand::ACTION => Action::MAIN,
                    Content::FILES => $jobsChunk,
                ]);
            });
        });

        /** @var string $serverAddress */
        $serverAddress = $tcpServer->getAddress();

        /** @var int $serverPort */
        $serverPort = parse_url($serverAddress, PHP_URL_PORT);

        $scheduledFilesCount = 0;
        foreach ($jobsPerWorker as $jobs) {
            $scheduledFilesCount += array_sum(array_map(count(...), $jobs));
        }

        $processedFilesCount = 0;
        $systemErrorsCount = 0;
        $reachedSystemErrorsCountLimit = false;

        $handleErrorCallable = function (Throwable $throwable) use (
            $parallelResultCollector,
            &$systemErrorsCount,
            &$reachedSystemErrorsCountLimit
        ): void {
            $parallelResultCollector->collectSystemError(new SystemError(
                $throwable->getMessage(),
                $throwable->getFile(),
                $throwable->getLine(),
            ));

            ++$systemErrorsCount;
            $reachedSystemErrorsCountLimit = $systemErrorsCount >= self::SYSTEM_ERROR_LIMIT;
            $this->processPool->quitAll();

            // give the child processes time to actually die, they can still write to cache meanwhile
            // @see https://github.com/rectorphp/rector-src/pull/3834/files#r1231696531
            sleep(1);
        };

        $timeoutInSeconds = SimpleParameterProvider::provideIntParameter(Option::PARALLEL_JOB_TIMEOUT_IN_SECONDS);
        $fileChunksBudgetPerProcess = [];
        $canForkWorkers = $this->canForkWorkers();

        // forked workers log their PHP errors next to the cache
        $cacheDirectory = SimpleParameterProvider::provideStringParameter(Option::CACHE_DIR);
        if ($canForkWorkers) {
            FileSystem::createDir($cacheDirectory);
        }

        $processSpawner = function (int $bucketKey) use (
            $parallelResultCollector,
            &$jobsPerWorker,
            &$bucketKeyByIdentifier,
            $postFileCallback,
            &$systemErrorsCount,
            &$reachedSystemErrorsCountLimit,
            $mainScript,
            $input,
            $serverPort,
            $streamSelectLoop,
            $timeoutInSeconds,
            $handleErrorCallable,
            $canForkWorkers,
            $cacheDirectory,
            &$fileChunksBudgetPerProcess,
            &$processSpawner,
            &$processedFilesCount
        ): void {
            $processIdentifier = Random::generate();
            $bucketKeyByIdentifier[$processIdentifier] = $bucketKey;
            $fileChunksBudgetPerProcess[$processIdentifier] = self::MAX_CHUNKS_PER_WORKER;

            if ($canForkWorkers) {
                $parallelProcess = new ParallelProcess(
                    '',
                    $streamSelectLoop,
                    $timeoutInSeconds,
                    $cacheDirectory,
                    $this->createForkedWorker($input, $processIdentifier, $serverPort)
                );
            } else {
                $workerCommandLine = $this->workerCommandLineFactory->create(
                    $mainScript,
                    ProcessCommand::class,
                    'worker',
                    $input,
                    $processIdentifier,
                    $serverPort,
                );

                $parallelProcess = new ParallelProcess(
                    $workerCommandLine,
                    $streamSelectLoop,
                    $timeoutInSeconds,
                    $cacheDirectory
                );
            }

            $parallelProcess->start(
                // 1. callable on data
                function (array $json) use (
                    $parallelProcess,
                    $parallelResultCollector,
                    &$jobsPerWorker,
                    &$bucketKeyByIdentifier,
                    $postFileCallback,
                    &$systemErrorsCount,
                    &$reachedSystemErrorsCountLimit,
                    $processIdentifier,
                    &$fileChunksBudgetPerProcess,
                    &$processSpawner,
                    &$processedFilesCount
                ): void {
                    /** @var array{
                     *      total_changed: int,
                     *      system_errors: mixed[],
                     *      file_diffs: array<string, mixed>,
                     *      files_count: int,
                     *      system_errors_count: int,
                     *      used_skips: array<string, string[]>
                     * } $json */
                    $filesCount = $parallelResultCollector->collectWorkerResult($json);
                    $postFileCallback($filesCount);
                    $processedFilesCount += $filesCount;

                    $systemErrorsCount += $json[Bridge::SYSTEM_ERRORS_COUNT];
                    if ($systemErrorsCount >= self::SYSTEM_ERROR_LIMIT) {
                        $reachedSystemErrorsCountLimit = true;
                        $this->processPool->quitAll();
                        return;
                    }

                    $hasPendingJobs = array_any($jobsPerWorker, static fn (array $jobs): bool => $jobs !== []);
                    if ($fileChunksBudgetPerProcess[$processIdentifier] <= 0 && $hasPendingJobs) {
                        // replace the current worker with a fresh one on the same bucket to free memory; spawn the
                        // replacement first, as quitting the last worker in the pool closes the server it connects to
                        ($processSpawner)($bucketKeyByIdentifier[$processIdentifier]);
                        $this->processPool->quitProcess($processIdentifier);
                        return;
                    }

                    $jobsChunk = $this->popJobChunk($processIdentifier, $jobsPerWorker, $bucketKeyByIdentifier);
                    if ($jobsChunk === null) {
                        // bucket done, this worker has nothing left to do
                        $this->processPool->quitProcess($processIdentifier);
                        return;
                    }

                    $parallelProcess->request([
                        ReactCommand::ACTION => Action::MAIN,
                        Content::FILES => $jobsChunk,
                    ]);
                    --$fileChunksBudgetPerProcess[$processIdentifier];
                },

                // 2. callable on error
                $handleErrorCallable,

                // 3. callable on exit
                function ($exitCode, string $stdErr) use ($parallelResultCollector, $processIdentifier): void {
                    $this->processPool->tryQuitProcess($processIdentifier);
                    if ($exitCode === Command::SUCCESS) {
                        return;
                    }

                    if ($exitCode === null) {
                        return;
                    }

                    $parallelResultCollector->collectSystemError(new SystemError(
                        'Child process error: ' . $stdErr
                    ));
                }
            );

            $this->processPool->attachProcess($processIdentifier, $parallelProcess);
        };

        foreach (array_keys($jobsPerWorker) as $bucketKey) {
            ($processSpawner)($bucketKey);
        }

        $streamSelectLoop->run();

        if ($reachedSystemErrorsCountLimit) {
            $parallelResultCollector->collectSystemError(new SystemError(sprintf(
                'Reached system errors count limit of %d, exiting...',
                self::SYSTEM_ERROR_LIMIT
            )));
        }

        // Workers may exit without reporting their assigned files.
        if ($processedFilesCount < $scheduledFilesCount) {
            $parallelResultCollector->collectSystemError(new SystemError(sprintf(
                'Some parallel jobs have not finished, results for %d of %d files are missing',
                $scheduledFilesCount - $processedFilesCount,
                $scheduledFilesCount
            )));
        }

        return $parallelResultCollector->createProcessResult();
    }

    /**
     * Forking skips starting PHP and booting the container again for every worker, including the ones that
     * replace a recycled worker. A fork runs on what the main process has loaded, so it is only used when a
     * spawned worker would not get more than that - PHPStan Turbo on its command line.
     */
    private function canForkWorkers(): bool
    {
        if (StaticPHPUnitEnvironment::isPHPUnitRun()) {
            return false;
        }

        if (! extension_loaded('pcntl') || ! extension_loaded('posix')) {
            return false;
        }

        $disabledFunctions = (string) ini_get('disable_functions');
        if (str_contains($disabledFunctions, 'pcntl_') || str_contains($disabledFunctions, 'posix_kill')) {
            return false;
        }

        return TurboExtensionEnabler::isLoaded() || TurboExtensionSelector::findExtensionForWorkers() === null;
    }

    /**
     * @return callable(): int
     */
    private function createForkedWorker(InputInterface $input, string $processIdentifier, int $serverPort): callable
    {
        $workerInput = new ArrayInput($this->workerCommandLineFactory->createInputParameters(
            ProcessCommand::class,
            $input,
            $processIdentifier,
            $serverPort
        ));

        return function () use ($workerInput): int {
            // the main process collects PHP errors on its own while it waits for the workers;
            // a worker leaves them to PHP, the same as a spawned one
            restore_error_handler();

            $workerCommand = ($this->workerCommandResolver)();

            return $workerCommand->run($workerInput, new ConsoleOutput());
        };
    }

    /**
     * @param array<int, array<int, array<string>>> $jobsPerWorker
     * @param array<string, int> $bucketKeyByIdentifier
     * @return array<string>|null
     */
    private function popJobChunk(
        string $processIdentifier,
        array &$jobsPerWorker,
        array $bucketKeyByIdentifier
    ): ?array {
        $bucketKey = $bucketKeyByIdentifier[$processIdentifier] ?? null;

        if ($bucketKey !== null && ($jobsPerWorker[$bucketKey] ?? []) !== []) {
            return array_pop($jobsPerWorker[$bucketKey]);
        }

        return $this->stealJobChunk($jobsPerWorker);
    }

    /**
     * Own bucket drained while others still have work. Byte size only estimates the cost of a file, and
     * not every core is equally fast - on a big.LITTLE CPU (Apple silicon, recent Intel) a bucket handed
     * to an efficiency core takes several times longer. Take over a chunk from whoever has the most left,
     * so the run is not held up by one straggler.
     *
     * @param array<int, array<int, array<string>>> $jobsPerWorker
     * @return array<string>|null
     */
    private function stealJobChunk(array &$jobsPerWorker): ?array
    {
        $fattestBucketKey = null;
        $fattestJobCount = 0;

        foreach ($jobsPerWorker as $currentBucketKey => $jobs) {
            $jobCount = count($jobs);
            if ($jobCount <= $fattestJobCount) {
                continue;
            }

            $fattestJobCount = $jobCount;
            $fattestBucketKey = $currentBucketKey;
        }

        if ($fattestBucketKey === null) {
            return null;
        }

        return array_pop($jobsPerWorker[$fattestBucketKey]);
    }
}
