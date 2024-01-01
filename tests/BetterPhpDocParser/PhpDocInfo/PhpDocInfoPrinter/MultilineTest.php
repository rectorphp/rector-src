<?php

declare(strict_types=1);

namespace Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter;

use Iterator;
use Nette\Utils\FileSystem;
use PhpParser\Node\Stmt\Nop;
use PHPUnit\Framework\Attributes\DataProvider;

final class MultilineTest extends AbstractPhpDocInfoPrinterTestCase
{
    #[DataProvider('provideData')]
    public function test(string $docFilePath): void
    {
        $nop = new Nop();

        $docComment = FileSystem::read($docFilePath);
        $phpDocInfo = $this->createPhpDocInfoFromDocCommentAndNode($docComment, $nop);
        $printedPhpDocInfo = $this->phpDocInfoPrinter->printFormatPreserving($phpDocInfo);

        $this->assertSame($docComment, $printedPhpDocInfo);
    }

    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Source/Multiline/multiline2.txt'];
        yield [__DIR__ . '/Source/Multiline/multiline3.txt'];

        yield [__DIR__ . '/Source/Class_/some_entity_class.txt'];
        yield [__DIR__ . '/Source/Multiline/table.txt'];

        yield [__DIR__ . '/Source/Multiline/assert_serialize.txt'];
        yield [__DIR__ . '/Source/Multiline/multiline6.txt'];
    }
}
