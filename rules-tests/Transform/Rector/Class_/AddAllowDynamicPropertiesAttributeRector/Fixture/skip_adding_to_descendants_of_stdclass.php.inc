<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture;

use stdClass;

class BasicObject extends stdClass
{
    public string $someProperty = 'hello world';
}

class AnotherObject extends BasicObject
{
    public string $someProperty = 'hello space';
}

?>
