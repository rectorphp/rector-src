<?php

declare(strict_types=1);

namespace Rector\Parallel\ValueObject;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Rector\Parallel\Exception\ParallelShouldNotHappenException;

/**
 * The command-line paths for the workers, in a temp file, as the OS limits the length of their command line.
 * The file is removed by remove(), on shutdown and, when ext-pcntl is loaded, on SIGINT and SIGTERM.
 *
 * @see \Rector\Tests\Parallel\ValueObject\WorkerPathsFileTest
 */
final class WorkerPathsFile
{
    private bool $isRemoved = false;

    /**
     * @var array<int, (callable(int, mixed): void)|int>
     */
    private array $previousSignalHandlers = [];

    private ?bool $previousAsyncSignals = null;

    private function __construct(
        private readonly string $filePath
    ) {
    }

    /**
     * @param string[] $paths
     */
    public static function create(array $paths): self
    {
        $filePath = tempnam(sys_get_temp_dir(), 'rector_paths_');
        if ($filePath === false) {
            throw new ParallelShouldNotHappenException('Failed creating temp file.');
        }

        FileSystem::write($filePath, Json::encode($paths));

        $workerPathsFile = new self($filePath);
        $workerPathsFile->removeOnInterrupt();

        return $workerPathsFile;
    }

    public function getFilePath(): string
    {
        return $this->filePath;
    }

    public function remove(): void
    {
        if ($this->isRemoved) {
            return;
        }

        $this->isRemoved = true;
        FileSystem::delete($this->filePath);

        foreach ($this->previousSignalHandlers as $signal => $previousSignalHandler) {
            pcntl_signal($signal, $previousSignalHandler);
        }

        if ($this->previousAsyncSignals !== null) {
            pcntl_async_signals($this->previousAsyncSignals);
        }
    }

    private function removeOnInterrupt(): void
    {
        register_shutdown_function($this->remove(...));

        if (! extension_loaded('pcntl')) {
            return;
        }

        $signalHandler = function (int $signal): never {
            $this->remove();

            // the conventional exit code of a process ended by a signal
            exit(128 + $signal);
        };

        foreach ([SIGINT, SIGTERM] as $signal) {
            $this->previousSignalHandlers[$signal] = pcntl_signal_get_handler($signal);
            pcntl_signal($signal, $signalHandler);
        }

        $this->previousAsyncSignals = pcntl_async_signals(true);
    }
}
