<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictParamRector\Source;

class ParentHasReturn {
    public function doFoo(int $param): int {
    }
}
