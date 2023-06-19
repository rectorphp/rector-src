<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

class SkipDynamicVariable
{
    public function bar()
    {
        $employee = (object) ['data' => ['dd1' => 1, 'dd2' => 2]];

        for ($i=1; $i <= 2; $i++) {
            ${'field'.$i} = $employee->data['dd'.$i];
        }
    }
}