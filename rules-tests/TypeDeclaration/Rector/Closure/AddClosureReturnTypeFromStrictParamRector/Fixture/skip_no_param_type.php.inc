<?php

namespace Rector\Tests\TypeDeclaration\Rector\Closure\AddClosureReturnTypeFromStrictParamRector\Fixture;

class SkipNoParamType
{
    public function doFoo() {
        $c = function ($param) {
            return $param;
        };
        return $c;
    }
}
