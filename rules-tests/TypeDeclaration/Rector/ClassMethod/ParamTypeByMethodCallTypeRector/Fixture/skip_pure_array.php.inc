<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class SkipPureArray
{
    public function go($value)
    {
        $this->use($value);
    }

    private function use(array $value): void
    {
    }
}
