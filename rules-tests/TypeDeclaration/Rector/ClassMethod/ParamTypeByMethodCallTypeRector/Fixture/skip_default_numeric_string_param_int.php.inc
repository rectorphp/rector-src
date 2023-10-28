<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ParamTypeByMethodCallTypeRector\Fixture;

final class SkipDefaultNumericStringParamInt
{
    public function go($value = '1')
    {
        $this->execute($value);
    }

    private function execute(int $value)
    {
    }
}
