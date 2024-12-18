<?php

namespace Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Fixture;

use Rector\Tests\Php81\Rector\New_\MyCLabsConstructorCallToEnumFromRector\Source\SomeClass;

class SkipConstructorCallForNonEnum
{
    public function someMethod(): SomeClass
    {
        return new SomeClass();
    }
}
?>
