<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayParamTypeByMethodCallTypeRector\Fixture;

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
