<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipMultipleReturns {
    public function doFoo(SkipMultipleReturns $param, B $param2) {
        if (rand(0,1)) {
            return $param2;
        }

        return $param;
    }
}

class B {}
