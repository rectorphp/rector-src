<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipAlreadyReturnTyped {
    public function doFoo(SkipAlreadyReturnTyped $param): int {
        return $param;
    }
}

