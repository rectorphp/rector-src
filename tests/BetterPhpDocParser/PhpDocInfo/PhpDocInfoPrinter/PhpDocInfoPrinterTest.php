<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter;

use Iterator;
use Nette\Utils\FileSystem;
use PhpParser\Node\Stmt\Nop;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\FileSystem\FilePathHelper;
use Rector\Testing\Fixture\FixtureFileFinder;

final class PhpDocInfoPrinterTest extends AbstractPhpDocInfoPrinterTestCase
{
    #[DataProvider('provideData')]
    #[DataProvider('provideDataCallable')]
    public function test(string $docFilePath): void
    {
        $this->doComparePrintedFileEquals($docFilePath, $docFilePath);
    }

    public function testRemoveSpace(): void
    {
        $this->doComparePrintedFileEquals(
            __DIR__ . '/FixtureChanged/with_space.txt',
            __DIR__ . '/FixtureChangedExpected/with_space_expected.txt'
        );
    }

    public static function provideData(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/FixtureBasic', '*.txt');
    }

    public static function provideDataCallable(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/FixtureCallable', '*.txt');
    }

    #[DataProvider('provideDataEmpty')]
    public function testEmpty(string $filePath): void
    {
        $fileContents = FileSystem::read($filePath);

        $phpDocInfo = $this->createPhpDocInfoFromDocCommentAndNode($fileContents, new Nop());
        $this->assertEmpty($this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo));
    }

    public static function provideDataEmpty(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/FixtureEmpty', '*.txt');
    }

    private function doComparePrintedFileEquals(string $inputFilePath, string $expectedFilePath): void
    {
        $inputFileContents = FileSystem::read($inputFilePath);
        $expectedFileContents = FileSystem::read($expectedFilePath);

        $phpDocInfo = $this->createPhpDocInfoFromDocCommentAndNode($inputFileContents, new Nop());
        $printedDocComment = $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);

        $filePathHelper = $this->make(FilePathHelper::class);
        $relativeInputFilePath = $filePathHelper->relativePath($inputFilePath);

        $this->assertSame($expectedFileContents, $printedDocComment, $relativeInputFilePath);
    }
}
