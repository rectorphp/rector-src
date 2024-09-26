<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\BoolReturnTypeFromBooleanStrictReturnsRector\Fixture;

class SkipFuncCallNotFound
{
    public function run()
    {
        return dt('some');
    }
}
