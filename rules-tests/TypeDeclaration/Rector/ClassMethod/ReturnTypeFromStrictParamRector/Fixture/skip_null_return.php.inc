<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipNullReturn {
    public function doFoo(SkipNullReturn $param) {
        if (rand(0,1)) {
            return null;
        }
        return $param;
    }
}
