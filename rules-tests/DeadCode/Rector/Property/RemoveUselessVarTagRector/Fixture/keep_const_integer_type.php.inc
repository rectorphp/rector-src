<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeConstantIntegerType;

final class KeepConstantIntegerType
{
    /**
     * @var SomeConstantIntegerType::*
     */
    public int $name = SomeConstantIntegerType::ONE;
}
