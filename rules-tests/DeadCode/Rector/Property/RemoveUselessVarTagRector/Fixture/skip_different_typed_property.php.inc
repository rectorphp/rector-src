<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\DifferentTypeProperty;
use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeMoreSpecificObject;

final class SkipDifferentTypedProperty
{
    /**
     * @var DifferentTypeProperty
     */
    public SomeMoreSpecificObject $name;
}
