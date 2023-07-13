<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipClosure {
    public function doFoo() {
        // we have other rules which add closure return types
        // leave it as is
        $c = function (SkipClosure $param) {
            return $param;
        };
        return $c;
    }
}
