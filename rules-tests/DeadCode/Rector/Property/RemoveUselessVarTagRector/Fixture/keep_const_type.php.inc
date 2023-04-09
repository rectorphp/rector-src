<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeConstantType;

final class KeepConstantType
{
    /**
     * @var SomeConstantIntegerType::*
     */
    public string $name = SomeConstantType::YES;
}
