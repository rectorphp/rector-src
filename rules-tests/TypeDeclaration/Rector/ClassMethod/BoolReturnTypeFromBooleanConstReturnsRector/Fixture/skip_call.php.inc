<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanConstReturnsRector\Fixture;

final class SkipCall
{
    public function resolve($first, $second)
    {
        return $first >= $second;
    }
}
