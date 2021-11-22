<?php

declare(strict_types=1);

namespace Rector\Parallel\Application;

use Closure;
use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use Nette\Utils\Random;
use React\EventLoop\StreamSelectLoop;
use React\Socket\ConnectionInterface;
use React\Socket\TcpServer;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\Error\SystemError;
use Rector\Core\ValueObject\Reporting\FileDiff;
use Rector\Parallel\Command\WorkerCommandLineFactory;
use Rector\Parallel\Enum\Action;
use Rector\Parallel\ValueObject\Bridge;
use Rector\Parallel\ValueObject\ParallelProcess;
use Rector\Parallel\ValueObject\ProcessPool;
use Rector\Parallel\ValueObject\ReactCommand;
use Rector\Parallel\ValueObject\ReactEvent;
use Rector\Parallel\ValueObject\Schedule;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Throwable;

/**
 * Inspired from @see
 * https://github.com/phpstan/phpstan-src/commit/9124c66dcc55a222e21b1717ba5f60771f7dda92#diff-39c7a3b0cbb217bbfff96fbb454e6e5e60c74cf92fbb0f9d246b8bebbaad2bb0
 *
 * https://github.com/phpstan/phpstan-src/commit/b84acd2e3eadf66189a64fdbc6dd18ff76323f67#diff-7f625777f1ce5384046df08abffd6c911cfbb1cfc8fcb2bdeaf78f337689e3e2R150
 *
 * @api @todo complete parallel run
 */
final class ParallelFileProcessor
{
    /**
     * @var int
     */
    public const TIMEOUT_IN_SECONDS = 60;

    /**
     * @var int
     */
    private const SYSTEM_ERROR_COUNT_LIMIT = 20;

    private ProcessPool|null $processPool = null;

    public function __construct(
        private WorkerCommandLineFactory $workerCommandLineFactory
    ) {
    }

    /**
     * @param Closure(int): void|null $postFileCallback Used for progress bar jump
     * @return mixed[]
     */
    public function check(
        Schedule $schedule,
        string $mainScript,
        Closure $postFileCallback,
        ?string $projectConfigFile,
        InputInterface $input
    ): array {
        $jobs = array_reverse($schedule->getJobs());
        $streamSelectLoop = new StreamSelectLoop();

        // basic properties setup
        $numberOfProcesses = $schedule->getNumberOfProcesses();

        // initial counters
        $fileDiffs = [];
        $systemErrors = [];

        $tcpServer = new TcpServer('127.0.0.1:0', $streamSelectLoop);
        $this->processPool = new ProcessPool($tcpServer);

        $tcpServer->on(ReactEvent::CONNECTION, function (ConnectionInterface $connection) use (&$jobs): void {
            $inDecoder = new Decoder($connection, true, 512, 0, 4 * 1024 * 1024);
            $outEncoder = new Encoder($connection);

            $inDecoder->on(ReactEvent::DATA, function (array $data) use (&$jobs, $inDecoder, $outEncoder): void {
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

                $job = array_pop($jobs);
                $parallelProcess->request([
                    ReactCommand::ACTION => Action::PROCESS,
                    'files' => $job,
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
                $throwable->getLine(),
                $throwable->getMessage(),
                $throwable->getFile()
            );

            ++$systemErrorsCount;
            $reachedSystemErrorsCountLimit = true;
            $this->processPool->quitAll();
        };

        for ($i = 0; $i < $numberOfProcesses; ++$i) {
            // nothing else to process, stop now
            if ($jobs === []) {
                break;
            }

            $processIdentifier = Random::generate();
            $workerCommandLine = $this->workerCommandLineFactory->create(
                $mainScript,
                $projectConfigFile,
                $input,
                $processIdentifier,
                $serverPort,
            );

            $parallelProcess = new ParallelProcess($workerCommandLine, $streamSelectLoop, self::TIMEOUT_IN_SECONDS);
            $parallelProcess->start(
                // 1. callable on data
                function (array $json) use (
                    $parallelProcess,
                    &$systemErrors,
                    &$fileDiffs,
                    &$jobs,
                    $postFileCallback,
                    &$systemErrorsCount,
                    &$reachedInternalErrorsCountLimit,
                    $processIdentifier
                ): void {

                    // decode arrays to objects
                    foreach ($json[Bridge::SYSTEM_ERRORS] as $jsonError) {
                        if (is_string($jsonError)) {
                            $systemErrors[] = 'System error: ' . $jsonError;
                            continue;
                        }

                        $systemErrors[] = SystemError::decode($jsonError);
                    }

                    foreach ($json[Bridge::FILE_DIFFS] as $jsonError) {
                        $fileDiffs[] = FileDiff::decode($jsonError);
                    }

                    // @todo why there is a null check?
                    if ($postFileCallback !== null) {
                        $postFileCallback($json[Bridge::FILES_COUNT]);
                    }

                    $systemErrorsCount += $json[Bridge::SYSTEM_ERRORS_COUNT];
                    if ($systemErrorsCount >= self::SYSTEM_ERROR_COUNT_LIMIT) {
                        $reachedInternalErrorsCountLimit = true;
                        $this->processPool->quitAll();
                    }

                    if ($jobs === []) {
                        $this->processPool->quitProcess($processIdentifier);
                        return;
                    }

                    $job = array_pop($jobs);
                    $parallelProcess->request([
                        ReactCommand::ACTION => Action::PROCESS,
                        'files' => $job,
                    ]);
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

                    $systemErrors[] = 'Child process error: ' . $stdErr;
                }
            );

            $this->processPool->attachProcess($processIdentifier, $parallelProcess);
        }

        $streamSelectLoop->run();

        if ($reachedSystemErrorsCountLimit) {
            $systemErrors[] = sprintf(
                'Reached system errors count limit of %d, exiting...',
                self::SYSTEM_ERROR_COUNT_LIMIT
            );
        }

        return [
            Bridge::FILE_DIFFS => $fileDiffs,
            Bridge::SYSTEM_ERRORS => $systemErrors,
            Bridge::SYSTEM_ERRORS_COUNT => count($systemErrors),
        ];
    }
}
