<?php

namespace Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\FixtureAllClasses;

use stdClass;

class ChildOfStdClass extends stdClass
{
    public string $someProperty = 'hello world';
}

class DescendantOfStdClass extends ChildOfStdClass
{
    public string $someProperty = 'hello space';
}

class GrandChildOfStdClass extends ChildOfStdClass
{
    public string $someProperty = 'hello universe';
}

?>
