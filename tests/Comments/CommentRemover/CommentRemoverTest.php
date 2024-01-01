<?php

declare(strict_types=1);

namespace Rector\Tests\Comments\CommentRemover;

use Iterator;
use Nette\Utils\FileSystem;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Comments\CommentRemover;
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\PhpParser\Printer\BetterStandardPrinter;
use Rector\Testing\Fixture\FixtureFileFinder;
use Rector\Testing\Fixture\FixtureSplitter;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class CommentRemoverTest extends AbstractLazyTestCase
{
    private CommentRemover $commentRemover;

    private FileInfoParser $fileInfoParser;

    private BetterStandardPrinter $betterStandardPrinter;

    protected function setUp(): void
    {
        $this->commentRemover = $this->make(CommentRemover::class);
        $this->fileInfoParser = $this->make(FileInfoParser::class);
        $this->betterStandardPrinter = $this->make(BetterStandardPrinter::class);
    }

    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        [$inputContents, $expectedOutputContents] = FixtureSplitter::split($filePath);
        $inputFilePath = FixtureTempFileDumper::dump($inputContents);

        $nodes = $this->fileInfoParser->parseFileInfoToNodesAndDecorate($inputFilePath);

        FileSystem::delete($inputFilePath);

        $nodesWithoutComments = $this->commentRemover->removeFromNode($nodes);

        $fileContent = $this->betterStandardPrinter->print($nodesWithoutComments);
        $fileContent = trim($fileContent);

        $expectedContent = trim($expectedOutputContents);
        $this->assertSame($fileContent, $expectedContent);

        // original nodes are not touched
        $originalContent = $this->betterStandardPrinter->print($nodes);
        $this->assertNotSame($expectedContent, $originalContent);
    }

    public static function provideData(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/Fixture');
    }
}
