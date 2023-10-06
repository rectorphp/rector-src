<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipConstructor
{
    public function __construct()
    {
        return true;
    }
}
