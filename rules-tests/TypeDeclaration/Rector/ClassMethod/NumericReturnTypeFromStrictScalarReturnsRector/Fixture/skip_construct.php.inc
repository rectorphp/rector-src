<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\NumericReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipConstruct
{
    public function __construct(int $first)
    {
        return ++$first;
    }
}
