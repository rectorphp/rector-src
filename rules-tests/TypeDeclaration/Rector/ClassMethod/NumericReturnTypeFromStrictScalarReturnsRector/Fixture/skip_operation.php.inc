<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipOperation
{
    public function resolve(int $value)
    {
        return ++$value;
    }
}
