<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipAlreadyReturnTyped {
    public function doFoo(SkipAssign $param): int {
        return $param;
    }
}

