<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Source\SomeConstantIntegerType;

final class KeepConstantIntegerType
{
    /**
     * @param SomeConstantIntegerType::* $type
     */
    public function run(int $type)
    {
    }
}
