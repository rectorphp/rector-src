<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipAssign {
    public function doFoo(SkipAssign $param) {
        $z = &$param;

        $z = 5;

        return $param;
    }
}

