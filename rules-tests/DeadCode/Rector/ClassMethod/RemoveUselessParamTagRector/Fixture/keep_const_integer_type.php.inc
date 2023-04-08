<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source\SomeConstantIntegerType;

final class KeepConstantIntegerType
{
    /**
     * @param SomeConstantIntegerType::* $type
     */
    public function run(int $type)
    {
    }
}

