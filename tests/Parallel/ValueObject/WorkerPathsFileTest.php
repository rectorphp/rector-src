<?php

declare(strict_types=1);

namespace Rector\Tests\Parallel\ValueObject;

use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use PHPUnit\Framework\Attributes\RequiresPhpExtension;
use PHPUnit\Framework\TestCase;
use Rector\Parallel\ValueObject\WorkerPathsFile;

final class WorkerPathsFileTest extends TestCase
{
    public function testWritesAndRemovesPaths(): void
    {
        $workerPathsFile = WorkerPathsFile::create(['src', 'tests/Some File.php']);
        $filePath = $workerPathsFile->getFilePath();

        $this->assertSame(['src', 'tests/Some File.php'], Json::decode(FileSystem::read($filePath), forceArrays: true));

        $workerPathsFile->remove();
        $this->assertFileDoesNotExist($filePath);

        // removing again, e.g. on shutdown after the run, is a no-op
        $workerPathsFile->remove();
        $this->assertFileDoesNotExist($filePath);
    }

    #[RequiresPhpExtension('pcntl')]
    public function testRestoresSignalHandlersOnRemove(): void
    {
        $previousSigintHandler = pcntl_signal_get_handler(SIGINT);
        $previousSigtermHandler = pcntl_signal_get_handler(SIGTERM);
        $previousAsyncSignals = pcntl_async_signals();

        $workerPathsFile = WorkerPathsFile::create(['src']);
        $this->assertNotSame($previousSigintHandler, pcntl_signal_get_handler(SIGINT));
        $this->assertNotSame($previousSigtermHandler, pcntl_signal_get_handler(SIGTERM));
        $this->assertTrue(pcntl_async_signals());

        $workerPathsFile->remove();

        $this->assertSame($previousSigintHandler, pcntl_signal_get_handler(SIGINT));
        $this->assertSame($previousSigtermHandler, pcntl_signal_get_handler(SIGTERM));
        $this->assertSame($previousAsyncSignals, pcntl_async_signals());
    }
}
