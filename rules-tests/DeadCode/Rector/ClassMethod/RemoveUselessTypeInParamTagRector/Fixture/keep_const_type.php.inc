<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Source\SomeConstantType;

final class KeepConstantType
{
    /**
     * @param SomeConstantType::* $type
     */
    public function run(string $type)
    {
    }
}
