<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source\SomeConstantType;

final class KeepConstantType
{
    /**
     * @param SomeConstantType::* $type
     */
    public function run(string $type)
    {
    }
}

