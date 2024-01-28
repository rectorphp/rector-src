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
use Rector\Exception\ShouldNotHappenException;
use Rector\PhpParser\Node\BetterNodeFinder;
use Rector\Testing\Fixture\FixtureSplitter;
use Rector\Testing\Fixture\FixtureTempFileDumper;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Rector\Testing\TestingParser\TestingParser;

final class TestModifyReprintTest extends AbstractLazyTestCase
{
    private TestingParser $testingParser;

    private BetterNodeFinder $betterNodeFinder;

    private PhpDocInfoPrinter $phpDocInfoPrinter;

    private PhpDocInfoFactory $phpDocInfoFactory;

    protected function setUp(): void
    {
        $this->testingParser = $this->make(TestingParser::class);
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

        $expectedDocContent = str_replace("\r\n", "\n", trim($expectedContent));

        $printedPhpDocInfo = $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
        $printedPhpDocInfo = str_replace("\r\n", "\n", $printedPhpDocInfo);

        $this->assertSame($expectedDocContent, $printedPhpDocInfo);
    }

    /**
     * @param class-string<Node> $nodeType
     */
    private function parseFileAndGetFirstNodeOfType(string $fileContents, string $nodeType): PhpDocInfo
    {
        $fixtureFilePath = FixtureTempFileDumper::dump($fileContents);
        $nodes = $this->testingParser->parseFileToDecoratedNodes($fixtureFilePath);

        FileSystem::delete($fixtureFilePath);

        $node = $this->betterNodeFinder->findFirstInstanceOf($nodes, $nodeType);
        if (! $node instanceof Node) {
            throw new ShouldNotHappenException($fileContents);
        }

        return $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
    }
}
