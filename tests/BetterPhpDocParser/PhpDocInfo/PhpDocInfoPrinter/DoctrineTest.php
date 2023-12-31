<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter;

use Iterator;
use Nette\Utils\FileSystem;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter\Source\Doctrine\CaseSensitive;
use Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter\Source\Doctrine\IndexInTable;
use Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter\Source\Doctrine\Short;

final class DoctrineTest extends AbstractPhpDocInfoPrinterTestCase
{
    #[DataProvider('provideDataClass')]
    public function testClass(string $docFilePath, string $className): void
    {
        $class = new Class_($className);

        $docComment = FileSystem::read($docFilePath);
        $phpDocInfo = $this->createPhpDocInfoFromDocCommentAndNode($docComment, $class);

        $printedPhpDocInfo = $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);
        $this->assertSame($docComment, $printedPhpDocInfo);
    }

    public static function provideDataClass(): Iterator
    {
        yield [__DIR__ . '/Source/Doctrine/index_in_table.txt', IndexInTable::class];
        yield [__DIR__ . '/Source/Doctrine/case_sensitive.txt', CaseSensitive::class];
        yield [__DIR__ . '/Source/Doctrine/short.txt', Short::class];
    }
}
