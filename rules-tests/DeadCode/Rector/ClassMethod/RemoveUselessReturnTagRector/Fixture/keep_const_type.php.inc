<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessReturnTagRector\Source\SomeConstantType;

final class KeepConstantType
{
    /**
     * @return SomeConstantType::*
     */
    public function run(): string
    {
    }
}
