<?php

namespace Rector\Tests\Php55\Rector\String_\StringClassNameToClassConstantRector\Fixture;

class SkipDynamicVariable2
{
    public function bar()
    {
        $dd1 = 1;

        for ($i=1; $i <= 2; $i++) {
            ${'field'.$i} = ${'dd'.$i};
        }
    }
}