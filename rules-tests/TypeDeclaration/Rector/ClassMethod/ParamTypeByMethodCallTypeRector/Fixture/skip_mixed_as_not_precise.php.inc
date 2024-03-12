<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class SkipMixedAsNotPrecise
{
    public function run($value)
    {
        $this->processArray($value);
    }

    private function processArray(mixed $value)
    {
    }
}
