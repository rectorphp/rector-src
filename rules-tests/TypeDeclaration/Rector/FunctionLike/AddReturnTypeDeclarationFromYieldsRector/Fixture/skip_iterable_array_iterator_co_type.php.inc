<?php

namespace Rector\Tests\TypeDeclaration\Rector\FunctionLike\AddReturnTypeDeclarationFromYieldsRector\Fixture;

use Iterator;
use Traversable;

final class SkipIterableArrayIteratorCoType
{
    public function run(): Iterator
    {
        yield 5;
    }

    public function run2(): Traversable
    {
        yield 5;
    }
}
