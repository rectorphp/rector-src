<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocParser\TagValueNodeReprint;

use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode;
use Rector\BetterPhpDocParser\PhpDoc\DoctrineAnnotationTagValueNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\BetterPhpDocParser\ValueObject\PhpDoc\DoctrineAnnotation\CurlyListNode;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\Core\PhpParser\Node\BetterNodeFinder;
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Rector\Core\Provider\CurrentFileProvider;
>>>>>>> make use of own FixtureSplitter
=======
use Rector\Core\Provider\CurrentFileProvider;
>>>>>>> 65b0df8d9d... fixup! misc
=======
>>>>>>> f92ffc3a20... fixup! fixup! misc
use Rector\FileSystemRector\Parser\FileInfoParser;
use Rector\Testing\Fixture\FixtureSplitter;
use Rector\Testing\PHPUnit\AbstractTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TestModifyReprintTest extends AbstractTestCase
{
    private FileInfoParser $fileInfoParser;

    private BetterNodeFinder $betterNodeFinder;

    private PhpDocInfoPrinter $phpDocInfoPrinter;

    private PhpDocInfoFactory $phpDocInfoFactory;

    protected function setUp(): void
    {
        $this->boot();

        $this->fileInfoParser = $this->getService(FileInfoParser::class);

        $this->betterNodeFinder = $this->getService(BetterNodeFinder::class);
        $this->phpDocInfoPrinter = $this->getService(PhpDocInfoPrinter::class);
        $this->phpDocInfoFactory = $this->getService(PhpDocInfoFactory::class);
    }

    public function test(): void
    {
        [$inputContent, $expectedContent] = FixtureSplitter::loadFileAndSplitInputAndExpected(
            __DIR__ . '/FixtureModify/route_with_extra_methods.php.inc'
        );

        $phpDocInfo = $this->parseFileAndGetFirstNodeOfType($inputContent, ClassMethod::class);

        /** @var DoctrineAnnotationTagValueNode $doctrineAnnotationTagValueNode */
        $doctrineAnnotationTagValueNode = $phpDocInfo->findOneByAnnotationClass(
            'Symfony\Component\Routing\Annotation\Route'
        );

        // this will extended tokens of first node
        $methodsCurlyListNode = new CurlyListNode([
            new ArrayItemNode('GET', null, String_::KIND_DOUBLE_QUOTED),
            new ArrayItemNode('HEAD', null, String_::KIND_DOUBLE_QUOTED),
        ]);
        $doctrineAnnotationTagValueNode->values[] = new ArrayItemNode($methodsCurlyListNode, 'methods');

        $expectedDocContent = trim((string) $expectedContent);

        $printedPhpDocInfo = $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
        $this->assertSame($expectedDocContent, $printedPhpDocInfo);
    }

    /**
     * @param class-string<Node> $nodeType
     */
    private function parseFileAndGetFirstNodeOfType(string $fileContent, string $nodeType): PhpDocInfo
    {
        // wrapping
        $tempFixtureFilePath = sys_get_temp_dir() . '/_rector/test_fixture_' . md5($fileContent) . '.php';
        FileSystem::write($tempFixtureFilePath, $fileContent);

        $smartFileInfo = new SmartFileInfo($tempFixtureFilePath);

        $nodes = $this->fileInfoParser->parseFileInfoToNodesAndDecorate($smartFileInfo);

        $node = $this->betterNodeFinder->findFirstInstanceOf($nodes, $nodeType);
        if (! $node instanceof Node) {
            throw new ShouldNotHappenException($smartFileInfo->getRealPath());
        }

        return $this->phpDocInfoFactory->createFromNodeOrEmpty($node);
    }
}
