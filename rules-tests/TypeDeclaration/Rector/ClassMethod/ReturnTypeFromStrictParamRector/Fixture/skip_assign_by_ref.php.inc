<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipAssignByRef {
    public function doFoo(SkipAssignByRef $param) {
        $z = &$param;

        $z = 5;

        return $param;
    }
}

