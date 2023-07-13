<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipCallbByRef {
    public function doFoo(SkipCallbByRef $param) {
        $this->doBar($param);

        return $param;
    }

    public function doBar(&$ref) {}
}

