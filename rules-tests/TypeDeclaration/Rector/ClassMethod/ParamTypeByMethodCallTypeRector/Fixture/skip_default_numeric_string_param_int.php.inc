<?php

declare(strict_types= 1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class SkipDefaultNumericStringParamInt
{
    public function go($value = '1')
    {
        return $this->execute($value);
    }

    private function execute(int $value)
    {
    }
}
