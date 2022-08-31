<?php

declare(strict_types=1);

namespace Rector\Core\Tests\FileSystem\FilesFinder;

use Iterator;
use Rector\Core\FileSystem\FilesFinder;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class FilesFinderTest extends AbstractTestCase
{
    private FilesFinder $filesFinder;

    protected function setUp(): void
    {
        $this->boot();
        $this->filesFinder = $this->getService(FilesFinder::class);
    }

    public function testDefault(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/config/default.php']);
        $filesFinder = $this->getService(FilesFinder::class);
        $foundFiles = $filesFinder->findInDirectoriesAndFiles([__DIR__ . '/SourceWithSymlinks'], ['txt']);
        $this->assertCount(1, $foundFiles);
    }

    public function testWithFollowingBrokenSymlinks(): void
    {
        $this->bootFromConfigFiles([__DIR__ . '/config/test_broken_symlinks.php']);
        $filesFinder = $this->getService(FilesFinder::class);
        $foundFiles = $filesFinder->findInDirectoriesAndFiles([__DIR__ . '/SourceWithBrokenSymlinks']);
        $this->assertCount(0, $foundFiles);
    }

    /**
     * @dataProvider provideData()
     */
    public function testSingleSuffix(string $suffix, int $count, string $expectedFileName): void
    {
        $foundFiles = $this->filesFinder->findInDirectoriesAndFiles([__DIR__ . '/Source'], [$suffix]);
        $this->assertCount($count, $foundFiles);

        /** @var string $foundFile */
        $foundFile = array_pop($foundFiles);
        $fileBasename = $this->getFileBasename($foundFile);

        $this->assertSame($expectedFileName, $fileBasename);
    }

    /**
     * @return Iterator<array<string|int>>
     */
    public function provideData(): Iterator
    {
        yield ['php', 1, 'SomeFile.php'];
        yield ['yml', 1, 'some_config.yml'];
        yield ['yaml', 1, 'other_config.yaml'];
        yield ['php', 1, 'SomeFile.php'];
    }

    public function testMultipleSuffixes(): void
    {
        $foundFiles = $this->filesFinder->findInDirectoriesAndFiles([__DIR__ . '/Source'], ['yaml', 'yml']);
        $this->assertCount(2, $foundFiles);

        $foundFileNames = [];
        foreach ($foundFiles as $foundFile) {
            $foundFileNames[] = $foundFile;
        }

        $expectedFoundFileNames = [__DIR__ . '/Source/some_config.yml', __DIR__ . '/Source/other_config.yaml'];

        sort($foundFileNames);
        sort($expectedFoundFileNames);
        $this->assertSame($expectedFoundFileNames, $foundFileNames);
    }

    public function testDirectoriesWithGlobPattern(): void
    {
        $foundDirectories = $this->filesFinder->findInDirectoriesAndFiles([__DIR__ . '/Source/folder*/*'], []);
        $this->assertCount(2, $foundDirectories);
    }

    public function testFilesWithGlobPattern(): void
    {
        $foundFiles = $this->filesFinder->findInDirectoriesAndFiles([__DIR__ . '/Source/**/foo.txt'], ['txt']);
        $this->assertCount(2, $foundFiles);

        /** @var string $foundFile */
        $foundFile = array_pop($foundFiles);

        $fileBasename = $this->getFileBasename($foundFile);
        $this->assertSame('foo.txt', $fileBasename);
    }

    private function getFileBasename(string $foundFile): string
    {
        return pathinfo($foundFile, PATHINFO_BASENAME);
    }
}
