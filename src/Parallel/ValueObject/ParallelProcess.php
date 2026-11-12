<?php

declare(strict_types=1);

namespace Rector\Parallel\ValueObject;

use Clue\React\NDJson\Decoder;
use Clue\React\NDJson\Encoder;
use Exception;
use Nette\Utils\FileSystem;
use React\ChildProcess\Process;
use React\EventLoop\LoopInterface;
use React\EventLoop\TimerInterface;
use Rector\Parallel\Enum\Action;
use Rector\Parallel\Enum\Content;
use Rector\Parallel\Enum\ReactCommand;
use Rector\Parallel\Enum\ReactEvent;
use Rector\Parallel\Exception\ParallelShouldNotHappenException;
use Throwable;

/**
 * Inspired at @see https://raw.githubusercontent.com/phpstan/phpstan-src/master/src/Parallel/Process.php
 * @see \Rector\Tests\Parallel\ValueObject\ParallelProcessTest
 */
final class ParallelProcess
{
    private Process $process;

    private Encoder $encoder;

    /**
     * @var resource|null
     */
    private $stdErr;

    /**
     * @var callable(mixed[]) : void
     */
    private $onData;

    /**
     * @var callable(Throwable): void
     */
    private $onError;

    private ?TimerInterface $timer = null;

    private ?int $forkedProcessId = null;

    private ?TimerInterface $forkedExitTimer = null;

    /**
     * @var (callable(): int)|null
     */
    private $forkedWorker;

    /**
     * @param (callable(): int)|null $forkedWorker Runs the worker in a fork of the current process and returns
     * its exit code. The fork starts with the container and the classes the main process already loaded,
     * instead of booting them again; the command is not used then.
     */
    public function __construct(
        private readonly string $command,
        private readonly LoopInterface $loop,
        private readonly int $timetoutInSeconds,
        private readonly string $cacheDirectory,
        ?callable $forkedWorker = null
    ) {
        $this->forkedWorker = $forkedWorker;
    }

    /**
     * @param callable(mixed[] $onData) : void $onData
     * @param callable(Throwable $onError) : void $onError
     * @param callable(?int $onExit, string $output) : void $onExit
     */
    public function start(callable $onData, callable $onError, callable $onExit): void
    {
        if ($this->forkedWorker !== null) {
            $this->onData = $onData;
            $this->onError = $onError;
            $this->startForked($onExit);
            return;
        }

        $tmp = tmpfile();
        if ($tmp === false) {
            throw new ParallelShouldNotHappenException('Failed creating temp file.');
        }

        $this->stdErr = $tmp;

        // on Unix, the command runs in a wrapping shell; exec replaces the shell with the worker,
        // so terminating the process stops the worker itself, not only the shell
        $command = DIRECTORY_SEPARATOR === '\\' ? $this->command : 'exec ' . $this->command;

        $this->process = new Process($command, null, null, [
            2 => $this->stdErr,
            // todo is it fine to not have 0 and 1 FD?
        ]);
        $this->process->start($this->loop);

        $this->onData = $onData;
        $this->onError = $onError;

        $this->process->on(ReactEvent::EXIT, function ($exitCode) use ($onExit): void {
            $stdErr = $this->stdErr;
            if ($stdErr === null) {
                throw new ParallelShouldNotHappenException();
            }

            $this->cancelTimer();

            rewind($stdErr);

            /** @var string $streamContents */
            $streamContents = stream_get_contents($stdErr);
            $onExit($exitCode, $streamContents);

            fclose($stdErr);
        });
    }

    /**
     * @param mixed[] $data
     */
    public function request(array $data): void
    {
        $this->cancelTimer();
        $this->encoder->write($data);
        $this->timer = $this->loop->addTimer($this->timetoutInSeconds, function (): void {
            // a worker that does not answer in time cannot be asked to stop either
            $this->terminate();

            $onError = $this->onError;

            $errorMessage = sprintf('Child process timed out after %d seconds', $this->timetoutInSeconds);
            $onError(new Exception($errorMessage));
        });
    }

    public function quit(): void
    {
        if (! $this->isRunning()) {
            return;
        }

        if (isset($this->process)) {
            foreach ($this->process->pipes as $pipe) {
                $pipe->close();
            }
        }

        // the process can be quit before its connection is bound, e.g. on quitAll() after an error;
        // such a worker cannot be asked to stop
        if (! isset($this->encoder)) {
            $this->terminate();
            return;
        }

        // a busy worker keeps its timeout, so it is still terminated when it never finishes its job
        $this->encoder->end();
    }

    public function bindConnection(Decoder $decoder, Encoder $encoder): void
    {
        $decoder->on(ReactEvent::DATA, function (array $json): void {
            $this->cancelTimer();
            if ($json[ReactCommand::ACTION] !== Action::RESULT) {
                return;
            }

            $onData = $this->onData;
            $onData($json[Content::RESULT]);
        });
        $this->encoder = $encoder;

        $decoder->on(ReactEvent::ERROR, function (Throwable $throwable): void {
            $onError = $this->onError;
            $onError($throwable);
        });

        $encoder->on(ReactEvent::ERROR, function (Throwable $throwable): void {
            $onError = $this->onError;
            $onError($throwable);
        });
    }

    /**
     * @param callable(?int $onExit, string $output) : void $onExit
     */
    private function startForked(callable $onExit): void
    {
        $errorLogFilePath = tempnam($this->cacheDirectory, 'rector-worker-');
        if ($errorLogFilePath === false) {
            throw new ParallelShouldNotHappenException('Failed creating the error log file of a worker.');
        }

        $processId = pcntl_fork();
        if ($processId === -1) {
            throw new ParallelShouldNotHappenException('Failed forking a worker process.');
        }

        if ($processId === 0) {
            $this->runForkedWorker($errorLogFilePath);
        }

        $this->forkedProcessId = $processId;

        $this->forkedExitTimer = $this->loop->addPeriodicTimer(0.01, function () use (
            $processId,
            $errorLogFilePath,
            $onExit
        ): void {
            $status = 0;
            if (pcntl_waitpid($processId, $status, WNOHANG) === 0) {
                return;
            }

            $this->forkedProcessId = null;
            $this->cancelTimer();

            if ($this->forkedExitTimer instanceof TimerInterface) {
                $this->loop->cancelTimer($this->forkedExitTimer);
                $this->forkedExitTimer = null;
            }

            // killed by a signal, e.g. after a timeout: no exit code, same as a spawned worker
            $exitCode = pcntl_wifexited($status) ? pcntl_wexitstatus($status) : null;
            $onExit($exitCode === false ? null : $exitCode, $this->readErrorLog($errorLogFilePath));
        });
    }

    /**
     * Runs in the fork. A spawned worker has its stderr captured; a fork shares the stderr of the main
     * process, so PHP logs its errors to a file instead.
     */
    private function runForkedWorker(string $errorLogFilePath): never
    {
        // bin/rector.php displays errors on stderr, which would print a crash of the fork on the terminal
        ini_set('display_errors', '0');

        // off in some php.ini files; the error log stays empty then and the reason of a crash is lost
        ini_set('log_errors', '1');
        ini_set('error_log', $errorLogFilePath);

        $forkedWorker = $this->forkedWorker;

        // never return to the code of the main process this fork was started from
        exit($forkedWorker === null ? 1 : $forkedWorker());
    }

    private function readErrorLog(string $errorLogFilePath): string
    {
        $errorLog = FileSystem::read($errorLogFilePath);
        FileSystem::delete($errorLogFilePath);

        return $errorLog;
    }

    private function isRunning(): bool
    {
        if ($this->forkedWorker !== null) {
            return $this->forkedProcessId !== null;
        }

        return $this->process->isRunning();
    }

    private function terminate(): void
    {
        if ($this->forkedProcessId !== null) {
            posix_kill($this->forkedProcessId, SIGTERM);
            return;
        }

        if (isset($this->process)) {
            $this->process->terminate();
        }
    }

    private function cancelTimer(): void
    {
        if (! $this->timer instanceof TimerInterface) {
            return;
        }

        $this->loop->cancelTimer($this->timer);
        $this->timer = null;
    }
}
