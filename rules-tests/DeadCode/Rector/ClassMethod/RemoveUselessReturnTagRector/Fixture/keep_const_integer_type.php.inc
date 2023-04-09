<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Source\SomeConstantIntegerType;

final class KeepConstantIntegerType
{
    /**
     * @return SomeConstantIntegerType::*
     */
    public function run(): int
    {
    }
}
