<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipAssign {
    public function doFoo(SkipAssign $param) {
        $param = 4;

        return $param;
    }
}

