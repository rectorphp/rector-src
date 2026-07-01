<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ObjectParamTypeByMethodCallTypeRector\Fixture;

final class SkipArrayType
{
    public function go($value)
    {
        $this->use($value);
    }

    private function use(array $value): void
    {
    }
}
