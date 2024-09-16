<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\DifferentTypeProperty;
use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeMoreSpecificObject;
use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeObject;

final class SkipMoreSpecificTypeInDocblock
{
    /**
     * @var SomeMoreSpecificObject
     */
    public SomeObject $name;
}
