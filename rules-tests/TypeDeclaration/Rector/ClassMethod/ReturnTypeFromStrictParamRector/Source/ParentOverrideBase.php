<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Source;

class ParentOverrideBase {
    public function doFoo(ParentOverrideBase $param) {
    }
}
