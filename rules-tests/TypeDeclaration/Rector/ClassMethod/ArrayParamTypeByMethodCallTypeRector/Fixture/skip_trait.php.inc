<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ArrayParamTypeByMethodCallTypeRector\Fixture;

trait SkipTrait
{
    public function go($value)
    {
        $this->use($value);
    }

    private function use(array $value): void
    {
    }
}
