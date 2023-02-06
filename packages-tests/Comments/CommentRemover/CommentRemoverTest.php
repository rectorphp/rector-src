<?php

declare(strict_types=1);

namespace Rector\Tests\Comments\CommentRemover;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Comments\CommentRemover;
use Rector\Core\Contract\PhpParser\NodePrinterInterface;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\Testing\Fixture\FixtureFileFinder;
use Rector\Testing\Fixture\FixtureSplitter;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class CommentRemoverTest extends AbstractTestCase
{
    private CommentRemover $commentRemover;

    private FileInfoParser $fileInfoParser;

    private NodePrinterInterface $nodePrinter;

    protected function setUp(): void
    {
        $this->boot();

        $this->commentRemover = $this->getService(CommentRemover::class);
        $this->fileInfoParser = $this->getService(FileInfoParser::class);
        $this->nodePrinter = $this->getService(BetterStandardPrinter::class);
    }

    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        [$inputContents, $expectedOutputContents] = FixtureSplitter::split($filePath);
        $inputFilePath = FixtureTempFileDumper::dump($inputContents);

        $nodes = $this->fileInfoParser->parseFileInfoToNodesAndDecorate($inputFilePath);
        $nodesWithoutComments = $this->commentRemover->removeFromNode($nodes);

        $fileContent = $this->nodePrinter->print($nodesWithoutComments);
        $fileContent = trim($fileContent);

        $expectedContent = trim((string) $expectedOutputContents);
        $this->assertSame($fileContent, $expectedContent);

        // original nodes are not touched
        $originalContent = $this->nodePrinter->print($nodes);
        $this->assertNotSame($expectedContent, $originalContent);
    }

    public static function provideData(): Iterator
    {
        return FixtureFileFinder::yieldDirectory(__DIR__ . '/Fixture');
    }
}
