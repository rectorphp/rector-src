<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector\Source\SomeConstantFloatType;

final class KeepConstantFloatType
{
    /**
     * @param SomeConstantFloatType::* $type
     */
    public function run(float $type)
    {
    }
}
