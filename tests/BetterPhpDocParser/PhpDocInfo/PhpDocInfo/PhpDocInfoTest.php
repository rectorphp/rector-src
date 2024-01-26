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
use Rector\NodeTypeResolver\Reflection\BetterReflection\SourceLocatorProvider\DynamicSourceLocatorProvider;
use Rector\StaticTypeMapper\ValueObject\Type\NonExistingObjectType;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class PhpDocInfoTest extends AbstractLazyTestCase
{
    private PhpDocInfo $phpDocInfo;

    private PhpDocInfoPrinter $phpDocInfoPrinter;

    private DocBlockTagReplacer $docBlockTagReplacer;

    protected function setUp(): void
    {
        // to avoid reflection parsing previous files
        $dynamicSourceLocatorProvider = $this->make(DynamicSourceLocatorProvider::class);
        $dynamicSourceLocatorProvider->reset();

        $this->phpDocInfoPrinter = $this->make(PhpDocInfoPrinter::class);
        $this->docBlockTagReplacer = $this->make(DocBlockTagReplacer::class);

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
        $printedPhpDocInfo = str_replace(PHP_EOL, "\n", $printedPhpDocInfo);

        $fileContent = str_replace(
            PHP_EOL,
            "\n",
            FileSystem::read(__DIR__ . '/Source/expected-replaced-tag.txt')
        );

        $this->assertSame(
            $fileContent,
            $printedPhpDocInfo
        );
    }

    public function testDoNotAddSpaseWhenAddEmptyString(): void
    {
        $this->phpDocInfo->addPhpDocTagNode(new PhpDocTextNode(''));
        $this->phpDocInfo->addPhpDocTagNode(new PhpDocTextNode('Some text'));

        $printedPhpDocInfo = $this->phpDocInfoPrinter->printFormatPreserving($this->phpDocInfo);
        $printedPhpDocInfo = str_replace(PHP_EOL, "\n", $printedPhpDocInfo);

        $fileContent = str_replace(
            PHP_EOL,
            "\n",
            FileSystem::read(__DIR__ . '/Source/expected-without-space-when-add-empty-string.txt')
        );

        $this->assertSame(
            $fileContent,
            $printedPhpDocInfo
        );
    }

    private function createPhpDocInfoFromFile(string $path): ?PhpDocInfo
    {
        $phpDocInfoFactory = $this->make(PhpDocInfoFactory::class);
        $phpDocContent = FileSystem::read($path);

        $nop = new Nop();
        $nop->setDocComment(new Doc($phpDocContent));

        return $phpDocInfoFactory->createFromNode($nop);
    }
}
