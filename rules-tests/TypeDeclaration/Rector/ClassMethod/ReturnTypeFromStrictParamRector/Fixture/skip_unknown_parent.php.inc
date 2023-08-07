<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipUnknownParent extends UnknownParentClass {
    public function doFoo(SkipParentOverridden $param) {
        return $param;
    }
}
