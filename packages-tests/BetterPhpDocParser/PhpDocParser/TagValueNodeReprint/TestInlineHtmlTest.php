<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint;

use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\CurlyListNode;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\Core\ValueObject\Application\File;
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TestInlineHtmlTest extends AbstractTestCase
{
    private FileInfoParser $fileInfoParser;

    private BetterNodeFinder $betterNodeFinder;

    private PhpDocInfoPrinter $phpDocInfoPrinter;

    private PhpDocInfoFactory $phpDocInfoFactory;

    private CurrentFileProvider $currentFileProvider;

    protected function setUp(): void
    {
        $this->boot();

        $this->fileInfoParser = $this->getService(FileInfoParser::class);

        $this->betterNodeFinder = $this->getService(BetterNodeFinder::class);
        $this->phpDocInfoPrinter = $this->getService(PhpDocInfoPrinter::class);
        $this->phpDocInfoFactory = $this->getService(PhpDocInfoFactory::class);
        $this->currentFileProvider = $this->getService(CurrentFileProvider::class);
    }

    public function test(): void
    {
        $fixtureFileInfo = new SmartFileInfo(__DIR__ . '/FixtureInlineHtml/expected-inline-html.php');

        $inputFileInfoAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($fixtureFileInfo);
        $inputFileInfo = $inputFileInfoAndExpected->getInputFileInfo();

        $this->currentFileProvider->setFile(new File($inputFileInfo, $inputFileInfo->getContents()));
        $phpDocInfo = $this->parseFileAndGetFirstNodeOfType($inputFileInfo, Node\Stmt\InlineHTML::class);

        $phpDocInfo->addTagValueNode(new VarTagValueNode(new IdentifierTypeNode('string'), '$hello', ''));

        $expectedDocContent = trim($inputFileInfoAndExpected->getExpected());

        $printedPhpDocInfo = $this->printPhpDocInfoToString($phpDocInfo);
        $this->assertSame($expectedDocContent, $printedPhpDocInfo);
    }

    /**
     * @param class-string<Node> $nodeType
     */
    private function parseFileAndGetFirstNodeOfType(SmartFileInfo $smartFileInfo, string $nodeType): PhpDocInfo
    {
        $nodes = $this->fileInfoParser->parseFileInfoToNodesAndDecorate($smartFileInfo);

        $node = $this->betterNodeFinder->findFirstInstanceOf($nodes, $nodeType);
        if (! $node instanceof Node) {
            throw new ShouldNotHappenException($smartFileInfo->getRealPath());
        }

        return $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
    }

    private function printPhpDocInfoToString(PhpDocInfo $phpDocInfo): string
    {
        // invoke re-print
        $phpDocInfo->markAsChanged();
        return $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
    }
}
