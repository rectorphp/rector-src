<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeConstantFloatType;

final class KeepConstantFloatType
{
    /**
     * @var SomeConstantFloatType::*
     */
    public float $name = SomeConstantFloatType::ONE_HALF;
}
