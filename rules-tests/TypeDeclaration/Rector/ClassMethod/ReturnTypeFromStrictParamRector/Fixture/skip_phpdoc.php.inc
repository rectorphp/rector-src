<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

class SkipPhpdoc {
    /**
     * @param SkipPhpdoc  $param
     */
    public function doFoo($param) {
        return $param;
    }
}

