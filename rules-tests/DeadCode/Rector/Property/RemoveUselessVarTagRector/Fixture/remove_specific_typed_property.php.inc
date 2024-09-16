<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeConstantFloatType;
use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeMoreSpecificObject;
use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeObject;

final class RemoveSpecificTypedProperty
{
    /**
     * @var SomeObject
     */
    public SomeMoreSpecificObject $name;
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Fixture;

use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeConstantFloatType;
use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeMoreSpecificObject;
use Rector\Tests\DeadCode\Rector\Property\RemoveUselessVarTagRector\Source\SomeObject;

final class RemoveSpecificTypedProperty
{
    public SomeMoreSpecificObject $name;
}

?>
