<?php

declare(strict_types=1);

namespace Rector\Tests\Comments\CommentRemover;

use Iterator;
use Rector\Comments\CommentRemover;
use Rector\Core\Contract\PhpParser\NodePrinterInterface;
use Rector\Core\PhpParser\Printer\BetterStandardPrinter;
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\EasyTesting\DataProvider\StaticFixtureFinder;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\SmartFileSystem\SmartFileInfo;

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

    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $smartFileInfo): void
    {
        $fileInfoToLocalInputAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($smartFileInfo);

        $nodes = $this->fileInfoParser->parseFileInfoToNodesAndDecorate(
            $fileInfoToLocalInputAndExpected->getInputFileInfo()
        );

        $nodesWithoutComments = $this->commentRemover->removeFromNode($nodes);

        $fileContent = $this->nodePrinter->print($nodesWithoutComments);
        $fileContent = trim($fileContent);

        $expectedContent = trim((string) $fileInfoToLocalInputAndExpected->getExpected());

        $this->assertSame($fileContent, $expectedContent, $smartFileInfo->getRelativeFilePathFromCwd());

        // original nodes are not touched
        $originalContent = $this->nodePrinter->print($nodes);
        $this->assertNotSame($expectedContent, $originalContent);
    }

    public function provideData(): Iterator
    {
        return StaticFixtureFinder::yieldDirectory(__DIR__ . '/Fixture', '*.php.inc');
    }
}
