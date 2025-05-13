<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

class SkipAccessedVariableBeforeAssign
{
    public $x;

    function __construct(string $x) {
        $x = 'prepended-' . $x;
        $this->x = $x;
    }
}
