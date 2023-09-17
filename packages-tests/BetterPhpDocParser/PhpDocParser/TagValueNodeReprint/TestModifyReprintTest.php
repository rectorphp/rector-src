<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDoc\StringNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\CurlyListNode;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\Testing\Fixture\FixtureSplitter;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class TestModifyReprintTest extends AbstractLazyTestCase
{
    private FileInfoParser $fileInfoParser;

    private BetterNodeFinder $betterNodeFinder;

    private PhpDocInfoPrinter $phpDocInfoPrinter;

    private PhpDocInfoFactory $phpDocInfoFactory;

    protected function setUp(): void
    {
        $this->fileInfoParser = $this->make(FileInfoParser::class);
        $this->betterNodeFinder = $this->make(BetterNodeFinder::class);
        $this->phpDocInfoPrinter = $this->make(PhpDocInfoPrinter::class);
        $this->phpDocInfoFactory = $this->make(PhpDocInfoFactory::class);
    }

    public function test(): void
    {
        [$inputContent, $expectedContent] = FixtureSplitter::split(
            __DIR__ . '/FixtureModify/route_with_extra_methods.php.inc'
        );

        $phpDocInfo = $this->parseFileAndGetFirstNodeOfType($inputContent, ClassMethod::class);

        $doctrineAnnotationTagValueNode = $phpDocInfo->findOneByAnnotationClass(
            'Symfony\Component\Routing\Annotation\Route'
        );
        $this->assertInstanceOf(DoctrineAnnotationTagValueNode::class, $doctrineAnnotationTagValueNode);

        // this will extended tokens of first node
        $methodsCurlyListNode = new CurlyListNode([
            new ArrayItemNode(new StringNode('GET')),
            new ArrayItemNode(new StringNode('HEAD')),
        ]);
        $doctrineAnnotationTagValueNode->values[] = new ArrayItemNode($methodsCurlyListNode, 'methods');

        $expectedDocContent = trim($expectedContent);

        $printedPhpDocInfo = $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
        $this->assertSame($expectedDocContent, $printedPhpDocInfo);
    }

    /**
     * @param class-string<Node> $nodeType
     */
    private function parseFileAndGetFirstNodeOfType(string $fileContents, string $nodeType): PhpDocInfo
    {
        $fixtureFilePath = FixtureTempFileDumper::dump($fileContents);
        $nodes = $this->fileInfoParser->parseFileInfoToNodesAndDecorate($fixtureFilePath);

        FileSystem::delete($fixtureFilePath);

        $node = $this->betterNodeFinder->findFirstInstanceOf($nodes, $nodeType);
        if (! $node instanceof Node) {
            throw new ShouldNotHappenException($fileContents);
        }

        return $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
    }
}
