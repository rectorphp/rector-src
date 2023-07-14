<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipParentOverridden extends SkipParentOverriddenBase {
    public function doFoo(SkipParentOverridden $param) {
        return $param;
    }
}


class SkipParentOverriddenBase {
    public function doFoo(SkipParentOverridden $param) {
    }
}
