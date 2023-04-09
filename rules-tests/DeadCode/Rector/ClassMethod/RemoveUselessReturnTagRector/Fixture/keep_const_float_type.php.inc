<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Source\SomeConstantFloatType;

final class KeepConstantFloatType
{
    /**
     * @return SomeConstantFloatType::*
     */
    public function run(): float
    {
    }
}
