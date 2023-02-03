<?php

declare(strict_types=1);

namespace Rector\Tests\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;

use Iterator;
use Nette\Utils\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;

final class FileWithoutNamespaceTest extends AbstractRectorTestCase
{
    /**
     * @param AddedFileWithContent[] $expectedFilePathsWithContents
     */
    #[DataProvider('provideData()')]
    public function test(
        string $originalFilePath,
        array $expectedFilePathsWithContents,
        bool $expectedOriginalFileWasRemoved = true
    ): void {
        $fixtureFilePath = FixtureTempFileDumper::dump(FileSystem::read($originalFilePath));
        $this->doTestFile($fixtureFilePath);

        $this->assertCount($this->removedAndAddedFilesCollector->getAddedFileCount(), $expectedFilePathsWithContents);
        $this->assertFilesWereAdded($expectedFilePathsWithContents);

        $isFileRemoved = $this->removedAndAddedFilesCollector->isFileRemoved($fixtureFilePath);

        $this->assertSame($expectedOriginalFileWasRemoved, $isFileRemoved);
    }

    /**
     * @return Iterator<mixed>
     */
    public static function provideData(): Iterator
    {
        $filePathsWithContents = [
            new AddedFileWithContent(
                $this->getFixtureTempDirectory() . '/SkipWithoutNamespace.php',
                FileSystem::read(__DIR__ . '/Expected/SkipWithoutNamespace.php')
            ),
            new AddedFileWithContent(
                $this->getFixtureTempDirectory() . '/JustTwoExceptionWithoutNamespace.php',
                FileSystem::read(__DIR__ . '/Expected/JustTwoExceptionWithoutNamespace.php')
            ),
        ];

        yield [__DIR__ . '/FixtureFileWithoutNamespace/some_without_namespace.php.inc', $filePathsWithContents];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
