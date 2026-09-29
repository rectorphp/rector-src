<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\ValueObject\Storage;

use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Symfony\Component\Filesystem\Filesystem;

final class FileCacheStorageTest extends AbstractLazyTestCase
{
    private FileCacheStorage $fileCacheStorage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileCacheStorage = new FileCacheStorage(__DIR__ . '/Source', new Filesystem());
    }

    #[DoesNotPerformAssertions]
    public function testCleanNonExistingFile(): void
    {
        $this->fileCacheStorage->clean('inexistant/file');
    }

    public function testClean(): void
    {
        $this->fileCacheStorage->save('aaK1STfY', 'TEST', 'file cached');
        $file1 = __DIR__ . '/Source/0e/76/0e76658526655756207688271159624026011393.php';

        $this->fileCacheStorage->save('aaO8zKZF', 'TEST', 'file cached with the same two first characters');
        $file2 = __DIR__ . '/Source/0e/89/0e89257456677279068558073954252716165668.php';

        $this->fileCacheStorage->clean('aaK1STfY');

        $this->assertFileDoesNotExist($file1);
        $this->assertDirectoryDoesNotExist(__DIR__ . '/Source/0e/76');

        $this->assertFileExists($file2);
        $this->assertDirectoryExists(__DIR__ . '/Source/0e/89');

        $this->fileCacheStorage->clean('aaO8zKZF');

        $this->assertFileDoesNotExist($file2);
        $this->assertDirectoryDoesNotExist(__DIR__ . '/Source/0e/89');
        $this->assertDirectoryDoesNotExist(__DIR__ . '/Source/0e');
    }

    public function testSaveLeavesConcurrentReaderOnCompleteFile(): void
    {
        if (\DIRECTORY_SEPARATOR === '\\') {
            // Windows blocks rename() over a file open in another handle, so the atomic-replace-under-open-reader
            // scenario this asserts is POSIX-only; on Windows writeAtomic() retries the transient lock instead
            $this->markTestSkipped('Atomic replace under an open reader is POSIX-only');
        }

        $filePath = __DIR__ . '/Source/0e/76/0e76658526655756207688271159624026011393.php';

        $this->fileCacheStorage->save('aaK1STfY', 'TEST', 'first');
        $contentsBeforeSave = (string) file_get_contents($filePath);

        // every parallel worker require()s this path while booting; open it as such a worker would,
        // then save over it mid-read - an atomic write must leave the reader on the file it opened
        $readerHandle = fopen($filePath, 'r');
        $this->assertNotFalse($readerHandle);

        $this->fileCacheStorage->save('aaK1STfY', 'TEST', 'second');

        $contentsSeenByReader = stream_get_contents($readerHandle);
        fclose($readerHandle);

        $this->assertSame($contentsBeforeSave, $contentsSeenByReader);
        $this->assertSame('second', $this->fileCacheStorage->load('aaK1STfY', 'TEST'));

        $this->fileCacheStorage->clean('aaK1STfY');
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config.php';
    }
}
