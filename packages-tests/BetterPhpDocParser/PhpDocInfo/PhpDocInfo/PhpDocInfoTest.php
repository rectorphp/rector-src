<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;

use Nette\Utils\FileSystem;
use PhpParser\Comment\Doc;
use PhpParser\Node\Stmt\Nop;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfoFactory;
use Rector\BetterPhpDocParser\Printer\PhpDocInfoPrinter;
use Rector\NodeTypeResolver\PhpDoc\NodeAnalyzer\DocBlockTagReplacer;
use Rector\StaticTypeMapper\ValueObject\Type\NonExistingObjectType;
use Rector\Testing\PHPUnit\AbstractTestCase;

final class PhpDocInfoTest extends AbstractTestCase
{
    private PhpDocInfo $phpDocInfo;

    private PhpDocInfoPrinter $phpDocInfoPrinter;

    private DocBlockTagReplacer $docBlockTagReplacer;

    protected function setUp(): void
    {
        $this->boot();

        $this->phpDocInfoPrinter = $this->getService(PhpDocInfoPrinter::class);
        $this->docBlockTagReplacer = $this->getService(DocBlockTagReplacer::class);

        $phpDocInfo = $this->createPhpDocInfoFromFile(__DIR__ . '/Source/doc.txt');
        $this->assertInstanceOf(PhpDocInfo::class, $phpDocInfo);

        $this->phpDocInfo = $phpDocInfo;
    }

    public function testGetTagsByName(): void
    {
        $paramTags = $this->phpDocInfo->getTagsByName('param');
        $this->assertCount(2, $paramTags);
    }

    public function testGetVarType(): void
    {
        $nonExistingObjectType = new NonExistingObjectType('SomeType');
        $this->assertEquals($nonExistingObjectType, $this->phpDocInfo->getVarType());
    }

    public function testGetReturnType(): void
    {
        $nonExistingObjectType = new NonExistingObjectType('SomeType');
        $this->assertEquals($nonExistingObjectType, $this->phpDocInfo->getReturnType());
    }

    public function testReplaceTagByAnother(): void
    {
        $phpDocInfo = $this->createPhpDocInfoFromFile(__DIR__ . '/Source/test-tag.txt');
        $this->assertInstanceOf(PhpDocInfo::class, $phpDocInfo);

        $this->docBlockTagReplacer->replaceTagByAnother($phpDocInfo, 'test', 'flow');

        $printedPhpDocInfo = $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
        $this->assertStringEqualsFile(__DIR__ . '/Source/expected-replaced-tag.txt', $printedPhpDocInfo);
    }

    public function testDoNotAddSpaseWhenAddEmptyString(): void
    {
        $this->phpDocInfo->addPhpDocTagNode(new PhpDocTextNode(''));
        $this->phpDocInfo->addPhpDocTagNode(new PhpDocTextNode('Some text'));

        $printedPhpDocInfo = $this->phpDocInfoPrinter->printFormatPreserving($this->phpDocInfo);
        $this->assertStringEqualsFile(
            __DIR__ . '/Source/expected-without-space-when-add-empty-string.txt',
            $printedPhpDocInfo
        );
    }

    private function createPhpDocInfoFromFile(string $path): ?PhpDocInfo
    {
        $phpDocInfoFactory = $this->getService(PhpDocInfoFactory::class);
        $phpDocContent = FileSystem::read($path);

        $nop = new Nop();
        $nop->setDocComment(new Doc($phpDocContent));

        return $phpDocInfoFactory->createFromNode($nop);
    }
}
