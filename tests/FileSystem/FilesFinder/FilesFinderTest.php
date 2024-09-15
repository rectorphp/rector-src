<?php

declare(strict_types=1);

namespace Rector\Tests\FileSystem\FilesFinder;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Configuration\Option;
use Rector\Configuration\Parameter\SimpleParameterProvider;
use Rector\FileSystem\FilesFinder;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class FilesFinderTest extends AbstractLazyTestCase
{
    private FilesFinder $filesFinder;

    protected function setUp(): void
    {
        $this->filesFinder = $this->make(FilesFinder::class);
    }

    public function test(): void
    {
        $foundFiles = $this->filesFinder->findInDirectoriesAndFiles([__DIR__ . '/SourceWithSymlinks'], ['txt']);
        $this->assertCount(1, $foundFiles);

        $foundFiles = $this->filesFinder->findInDirectoriesAndFiles([__DIR__ . '/SourceWithShortEchoes'], ['php']);
        $this->assertCount(0, $foundFiles);
    }

    #[DataProvider('alwaysReturnsAbsolutePathDataProvider')]
    public function testAlwaysReturnsAbsolutePath(string $relativePath): void
    {
        $absolutePath = str_replace('/', DIRECTORY_SEPARATOR, getcwd() . '/' . $relativePath);
        $foundFiles = $this->filesFinder->findInDirectoriesAndFiles([$absolutePath], ['php']);
        $this->assertStringStartsWith(
            $absolutePath,
            $foundFiles[0],
            'should return absolute path if absolute is given'
        );

        $foundFiles = $this->filesFinder->findInDirectoriesAndFiles([$relativePath], ['php']);
        $this->assertStringStartsWith(
            $absolutePath,
            $foundFiles[0],
            'should return absolute path if relative is given'
        );
    }

    /**
     * @return Iterator<array<string>>
     */
    public static function alwaysReturnsAbsolutePathDataProvider(): Iterator
    {
        yield 'directory given' => ['tests/FileSystem/FilesFinder/Source/'];
        yield 'file given' => ['tests/FileSystem/FilesFinder/Source/SomeFile.php'];
    }

    public function testWithFollowingBrokenSymlinks(): void
    {
        SimpleParameterProvider::setParameter(Option::SKIP, [__DIR__ . '/../SourceWithBrokenSymlinks/folder1']);

        $foundFiles = $this->filesFinder->findInDirectoriesAndFiles([__DIR__ . '/SourceWithBrokenSymlinks']);
        $this->assertCount(0, $foundFiles);
    }

    #[DataProvider('provideData')]
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
    public static function provideData(): Iterator
    {
        yield ['php', 1, 'SomeFile.php'];
        yield ['yml', 1, 'some_config.yml'];
        yield ['yaml', 1, 'other_config.yaml'];
        yield ['php', 1, 'SomeFile.php'];
    }

    public function testMultipleSuffixes(): void
    {
        $foundFilePaths = $this->filesFinder->findInDirectoriesAndFiles([__DIR__ . '/Source'], ['yaml', 'yml']);
        $this->assertCount(2, $foundFilePaths);

        $expectedFoundFilePath = [
            __DIR__ . DIRECTORY_SEPARATOR . 'Source' . DIRECTORY_SEPARATOR . 'some_config.yml',
            __DIR__ . DIRECTORY_SEPARATOR . 'Source' . DIRECTORY_SEPARATOR . 'other_config.yaml',
        ];

        sort($foundFilePaths);
        sort($expectedFoundFilePath);

        $this->assertSame($expectedFoundFilePath, $foundFilePaths);
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
