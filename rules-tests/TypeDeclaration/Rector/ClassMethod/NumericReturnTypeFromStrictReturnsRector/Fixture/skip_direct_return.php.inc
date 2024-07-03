<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictReturnsRector\Fixture;

final class SkipDirectReturn
{
    public function resolve()
    {
        return 1000;
    }

    public function next()
    {
        return -400;
    }
}
