<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayParamTypeByMethodCallTypeRector\Fixture;

final class SkipScalarType
{
    public function go($value)
    {
        $this->use($value);
    }

    private function use(string $value): void
    {
    }
}
