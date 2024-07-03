<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StringReturnTypeFromStrictStringReturnsRector\Fixture;

final class SkipBooleans
{
    public function run($value)
    {
        return false;
    }
}
