<?php

namespace Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Fixture;

use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterface;
use Rector\Tests\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector\Source\SomeInterfaceImplementation;

final class SkipNarrowerParamTypeWithOtherAssign
{
    private SomeInterface $x;

    public function __construct(SomeInterfaceImplementation $x)
    {
        $this->x = $x;
    }

    public function change(SomeInterface $x): void
    {
        $this->x = $x;
    }
}
