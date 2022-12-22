<?php

declare(strict_types=1);

namespace Rector\Tests\Caching\ValueObject\Storage;

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symfony\Component\Filesystem\Filesystem;

final class FileCacheStorageTest extends AbstractRectorTestCase
{
    private FileCacheStorage $fileCacheStorage;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileCacheStorage = new FileCacheStorage(__DIR__ . '/Source', new Filesystem());
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testCleanNonExistingFile(): void
    {
        $this->fileCacheStorage->clean('inexistant/file');
    }

    public function testClean(): void
    {
        $this->fileCacheStorage->save('aaK1STfY', 'TEST', 'file cached');
        $file1 = __DIR__ . '/Source/0e/76/0e76658526655756207688271159624026011393.php';

        $this->fileCacheStorage->save('aaO8zKZF', 'TEST', 'file cached with the same two first caracters');
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

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config.php';
    }
}
