<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipConditionalReturn {
    public function doFoo(SkipAssign $param) {
        if (rand(0,1)) {
            return 4;
        }

        return $param;
    }
}

