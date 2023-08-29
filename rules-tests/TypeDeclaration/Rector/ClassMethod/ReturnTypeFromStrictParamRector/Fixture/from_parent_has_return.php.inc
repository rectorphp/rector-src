<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Source\ParentHasReturn;

class FromParentHasReturn extends ParentHasReturn {
    public function doFoo(int $param): int {
        return $param;
    }
}

?>