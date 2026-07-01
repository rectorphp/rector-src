<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ScalarParamTypeByMethodCallTypeRector\Fixture;

use stdClass;

final class SkipObjectType
{
    public function go($value)
    {
        $this->use($value);
    }

    private function use(stdClass $value): void
    {
    }
}
