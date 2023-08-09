<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter;

use Iterator;
use Nette\Utils\FileSystem;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Node\Stmt\Property;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter\Source\Class_\SomeEntityClass;
use Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter\Source\TableClass;

final class MultilineTest extends AbstractPhpDocInfoPrinterTestCase
{
    #[DataProvider('provideData')]
    public function test(string $docFilePath, Node $node): void
    {
        $docComment = FileSystem::read($docFilePath);
        $phpDocInfo = $this->createPhpDocInfoFromDocCommentAndNode($docComment, $node);

        $printedPhpDocInfo = $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
        $this->assertSame($docComment, $printedPhpDocInfo);
    }

    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Source/Multiline/multiline2.txt', new Nop()];
        yield [__DIR__ . '/Source/Multiline/multiline3.txt', new Nop()];

        // class
        yield [__DIR__ . '/Source/Class_/some_entity_class.txt', new Class_(SomeEntityClass::class)];
        yield [__DIR__ . '/Source/Multiline/table.txt', new Class_(TableClass::class)];

        $property = self::createPublicPropertyUnderClass('anotherProperty');
        yield [__DIR__ . '/Source/Multiline/assert_serialize.txt', $property];

        $property = self::createPublicPropertyUnderClass('someProperty');
        yield [__DIR__ . '/Source/Multiline/multiline6.txt', $property];
    }

    private static function createPublicPropertyUnderClass(string $name): Property
    {
        $builderFactory = new BuilderFactory();

        $propertyBuilder = $builderFactory->property($name);
        $propertyBuilder->makePublic();

        return $propertyBuilder->getNode();
    }
}
