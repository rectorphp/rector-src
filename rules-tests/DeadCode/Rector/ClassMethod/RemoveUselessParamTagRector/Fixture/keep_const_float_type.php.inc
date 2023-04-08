<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessParamTagRector\Source\SomeConstantFloatType;

final class KeepConstantFloatType
{
    /**
     * @param SomeConstantFloatType::* $type
     */
    public function run(float $type)
    {
    }
}

