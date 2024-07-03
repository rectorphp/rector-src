<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

final class SkipDirectReturn
{
    public function resolve($first)
    {
        if ($first) {
            return false;
        }

        return true;
    }
}

