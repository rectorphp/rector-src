<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipAssign {
    public function doFoo(SkipAssign $param) {
        $this->doBar($param);

        return $param;
    }

    public function doBar(&$ref) {}
}

