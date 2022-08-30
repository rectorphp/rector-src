<?php

declare(strict_types=1);

namespace Rector\Tests\PSR4\Rector\Namespace_\MultipleClassFileToPsr4ClassesRector;

use Iterator;
use Nette\Utils\FileSystem;
use Rector\FileSystemRector\ValueObject\AddedFileWithContent;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Testing\PHPUnit\AbstractRectorTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class FileWithoutNamespaceTest extends AbstractRectorTestCase
{
    /**
     * @param AddedFileWithContent[] $expectedFilePathsWithContents
     * @dataProvider provideData()
     */
    public function test(
        SmartFileInfo $originalFileInfo,
        array $expectedFilePathsWithContents,
        bool $expectedOriginalFileWasRemoved = true
    ): void {
        $this->doTestFileInfo($originalFileInfo);

        $this->assertCount($this->removedAndAddedFilesCollector->getAddedFileCount(), $expectedFilePathsWithContents);

        $this->assertFilesWereAdded($expectedFilePathsWithContents);

        $fixtureFileInfo = FixtureTempFileDumper::dump($originalFileInfo->getContents());

        $this->assertSame(
            $expectedOriginalFileWasRemoved,
            $this->removedAndAddedFilesCollector->isFileRemoved($fixtureFileInfo)
        );
    }

    /**
     * @return Iterator<mixed>
     */
    public function provideData(): Iterator
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

        yield [
            new SmartFileInfo(__DIR__ . '/FixtureFileWithoutNamespace/some_without_namespace.php.inc'),
            $filePathsWithContents,
        ];
    }

    public function provideConfigFilePath(): string
    {
        return __DIR__ . '/config/configured_rule.php';
    }
}
