<?php

declare(strict_types=1);

namespace Rector\Parallel\Application;

use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use Nette\Utils\Random;
use PHPStan\Collectors\CollectedData;
use React\EventLoop\StreamSelectLoop;
use React\Socket\ConnectionInterface;
use React\Socket\TcpServer;
use Rector\Core\Configuration\Option;
use Rector\Core\Configuration\Parameter\SimpleParameterProvider;
use Rector\Core\Console\Command\ProcessCommand;
use Rector\Core\ValueObject\Configuration;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\ProcessResult;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\Command\WorkerCommandLineFactory;
use Rector\Parallel\ValueObject\Bridge;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symplify\EasyParallel\Enum\Action;
use Symplify\EasyParallel\Enum\Content;
use Symplify\EasyParallel\Enum\ReactCommand;
use Symplify\EasyParallel\Enum\ReactEvent;
use Symplify\EasyParallel\ValueObject\ParallelProcess;
use Symplify\EasyParallel\ValueObject\ProcessPool;
use Symplify\EasyParallel\ValueObject\Schedule;
use Throwable;

/**
 * Inspired from @see
 * https://github.com/phpstan/phpstan-src/commit/9124c66dcc55a222e21b1717ba5f60771f7dda92#diff-39c7a3b0cbb217bbfff96fbb454e6e5e60c74cf92fbb0f9d246b8bebbaad2bb0
 *
 * https://github.com/phpstan/phpstan-src/commit/b84acd2e3eadf66189a64fdbc6dd18ff76323f67#diff-7f625777f1ce5384046df08abffd6c911cfbb1cfc8fcb2bdeaf78f337689e3e2R150
 */
final class ParallelFileProcessor
{
    /**
     * @var int
     */
    private const SYSTEM_ERROR_LIMIT = 50;

    /**
     * The number of chunks a worker can process before getting killed.
     * In contrast the jobSize defines the maximum size of a chunk, a worker process at a time.
     *
     * @var int
     */
    private const MAX_CHUNKS_PER_WORKER = 8;

    private ProcessPool|null $processPool = null;

    public function __construct(
        private readonly WorkerCommandLineFactory $workerCommandLineFactory,
    ) {
    }

    /**
     * @param callable(int $stepCount): void $postFileCallback Used for progress bar jump
     */
    public function process(
        Schedule $schedule,
        string $mainScript,
        callable $postFileCallback,
        InputInterface $input,
        Configuration $configuration
    ): ProcessResult {
        $jobs = array_reverse($schedule->getJobs());
        $streamSelectLoop = new StreamSelectLoop();

        // basic properties setup
        $numberOfProcesses = $schedule->getNumberOfProcesses();

        // initial counters

        /** @var FileDiff[] $fileDiffs */
        $fileDiffs = [];

        /** @var CollectedData[] $collectedDatas */
        $collectedDatas = [];

        /** @var SystemError[] $systemErrors */
        $systemErrors = [];

        $tcpServer = new TcpServer('127.0.0.1:0', $streamSelectLoop);
        $this->processPool = new ProcessPool($tcpServer);

        $tcpServer->on(ReactEvent::CONNECTION, function (ConnectionInterface $connection) use (
            &$jobs,
            $configuration
        ): void {
            $inDecoder = new Decoder($connection, true, 512, 0, 4 * 1024 * 1024);
            $outEncoder = new Encoder($connection);

            $inDecoder->on(ReactEvent::DATA, function (array $data) use (
                &$jobs,
                $inDecoder,
                $outEncoder,
                $configuration
            ): void {
                $action = $data[ReactCommand::ACTION];
                if ($action !== Action::HELLO) {
                    return;
                }

                $processIdentifier = $data[Option::PARALLEL_IDENTIFIER];
                $parallelProcess = $this->processPool->getProcess($processIdentifier);
                $parallelProcess->bindConnection($inDecoder, $outEncoder);

                if ($jobs === []) {
                    $this->processPool->quitProcess($processIdentifier);
                    return;
                }

                $jobsChunk = array_pop($jobs);
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

        $systemErrorsCount = 0;
        $reachedSystemErrorsCountLimit = false;

        $handleErrorCallable = function (Throwable $throwable) use (
            &$systemErrors,
            &$systemErrorsCount,
            &$reachedSystemErrorsCountLimit
        ): void {
            $systemErrors[] = new SystemError(
                $throwable->getMessage(),
                $throwable->getFile(),
                $throwable->getLine(),
            );

            ++$systemErrorsCount;
            $reachedSystemErrorsCountLimit = true;
            $this->processPool->quitAll();

            // This sleep has to be here, because event though we have called $this->processPool->quitAll(),
            // it takes some time for the child processes to actually die, during which they can still write to cache
            // @see https://github.com/rectorphp/rector-src/pull/3834/files#r1231696531
            sleep(1);
        };

        $timeoutInSeconds = SimpleParameterProvider::provideIntParameter(Option::PARALLEL_JOB_TIMEOUT_IN_SECONDS);
        $fileChunksBudgetPerProcess = [];

        $processSpawner = function () use (
            &$systemErrors,
            &$fileDiffs,
            &$jobs,
            $postFileCallback,
            &$systemErrorsCount,
            &$reachedInternalErrorsCountLimit,
            $mainScript,
            $input,
            $serverPort,
            $streamSelectLoop,
            $timeoutInSeconds,
            $handleErrorCallable,
            &$fileChunksBudgetPerProcess,
            &$processSpawner
        ): void {
            $processIdentifier = Random::generate();
            $workerCommandLine = $this->workerCommandLineFactory->create(
                $mainScript,
                ProcessCommand::class,
                'worker',
                $input,
                $processIdentifier,
                $serverPort,
            );
            $fileChunksBudgetPerProcess[$processIdentifier] = self::MAX_CHUNKS_PER_WORKER;

            $parallelProcess = new ParallelProcess($workerCommandLine, $streamSelectLoop, $timeoutInSeconds);

            $parallelProcess->start(
                // 1. callable on data
                function (array $json) use (
                    $parallelProcess,
                    &$systemErrors,
                    &$fileDiffs,
                    &$jobs,
                    $postFileCallback,
                    &$systemErrorsCount,
                    &$collectedDatas,
                    &$reachedInternalErrorsCountLimit,
                    $processIdentifier,
                    &$fileChunksBudgetPerProcess,
                    &$processSpawner
                ): void {
                    // decode arrays to objects
                    foreach ($json[Bridge::SYSTEM_ERRORS] as $jsonError) {
                        if (is_string($jsonError)) {
                            $systemErrors[] = new SystemError('System error: ' . $jsonError);
                            continue;
                        }

                        $systemErrors[] = SystemError::decode($jsonError);
                    }

                    foreach ($json[Bridge::FILE_DIFFS] as $jsonFileDiff) {
                        $fileDiffs[] = FileDiff::decode($jsonFileDiff);
                    }

                    foreach ($json[Bridge::COLLECTED_DATA] as $jsonCollectedData) {
                        $collectedDatas[] = CollectedData::decode($jsonCollectedData);
                    }

                    $postFileCallback($json[Bridge::FILES_COUNT]);

                    $systemErrorsCount += $json[Bridge::SYSTEM_ERRORS_COUNT];
                    if ($systemErrorsCount >= self::SYSTEM_ERROR_LIMIT) {
                        $reachedInternalErrorsCountLimit = true;
                        $this->processPool->quitAll();
                    }

                    if ($fileChunksBudgetPerProcess[$processIdentifier] <= 0) {
                        // kill the current worker, and spawn a fresh one to free memory
                        $this->processPool->quitProcess($processIdentifier);

                        ($processSpawner)();
                        return;
                    }

                    if ($jobs === []) {
                        $this->processPool->quitProcess($processIdentifier);
                        return;
                    }

                    $jobsChunk = array_pop($jobs);
                    $parallelProcess->request([
                        ReactCommand::ACTION => Action::MAIN,
                        Content::FILES => $jobsChunk,
                    ]);
                    --$fileChunksBudgetPerProcess[$processIdentifier];
                },

                // 2. callable on error
                $handleErrorCallable,

                // 3. callable on exit
                function ($exitCode, string $stdErr) use (&$systemErrors, $processIdentifier): void {
                    $this->processPool->tryQuitProcess($processIdentifier);
                    if ($exitCode === Command::SUCCESS) {
                        return;
                    }

                    if ($exitCode === null) {
                        return;
                    }

                    $systemErrors[] = new SystemError('Child process error: ' . $stdErr);
                }
            );

            $this->processPool->attachProcess($processIdentifier, $parallelProcess);
        };

        for ($i = 0; $i < $numberOfProcesses; ++$i) {
            // nothing else to process, stop now
            if ($jobs === []) {
                break;
            }

            ($processSpawner)();
        }

        $streamSelectLoop->run();

        if ($reachedSystemErrorsCountLimit) {
            $systemErrors[] = new SystemError(sprintf(
                'Reached system errors count limit of %d, exiting...',
                self::SYSTEM_ERROR_LIMIT
            ));
        }

        return new ProcessResult($systemErrors, $fileDiffs, $collectedDatas);
    }
}
