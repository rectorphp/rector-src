<?php

namespace Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Fixture;

use Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Source\SomeEnumWithConstructor;

class SkipConstructorCallForCustomConstructor
{
    public function someMethod(): SomeEnumWithConstructor
    {
        return new SomeEnumWithConstructor('value');
    }
}
?>
