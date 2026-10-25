<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassLike\AddVoidReturnTypeWhereNoReturnRector\Fixture;

final class SkipYield
{
    public function getValues()
    {
        yield [1000];
    }
}
