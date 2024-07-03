<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictScalarReturnsRector\Fixture;

final class SkipReturnConcat
{
    public function run($value)
    {
        return $value . '/' ;
    }
}
