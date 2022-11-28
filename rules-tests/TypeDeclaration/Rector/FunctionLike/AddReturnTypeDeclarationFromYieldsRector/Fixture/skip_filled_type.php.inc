<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

use Iterator;
use PhpParser\Node\Stmt\Class_;
use Rector\Tests\BetterPhpDocParser\PhpDocInfo\PhpDocInfoPrinter\Source\Doctrine\IndexInTable;

final class SkipFilledType
{
    public function provideDataClass(): Iterator
    {
        yield [__DIR__ . '/../Source/some_file.txt', new Class_(IndexInTable::class)];
        yield [__DIR__ . '/../Source/some_file.txt', new Class_(IndexInTable::class)];
    }
}
