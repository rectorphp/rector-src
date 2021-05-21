<?php

namespace Rector\Tests\Transform\Rector\PropertyFetch\ClassPropertyFetchToClassMethodCallRector\Fixture;

use Rector\Tests\Transform\Rector\PropertyFetch\ClassPropertyFetchToClassMethodCallRector\Source\SomeOtherClass;

class SomeClass
{
    public function run()
    {
        $otherClass = new SomeOtherClass();
        $otherClass->property;
        $otherClass->property2;
    }
}

?>
-----
<?php

namespace Rector\Tests\Transform\Rector\PropertyFetch\ClassPropertyFetchToClassMethodCallRector\Fixture;

use Rector\Tests\Transform\Rector\PropertyFetch\ClassPropertyFetchToClassMethodCallRector\Source\SomeOtherClass;

class SomeClass
{
    public function run()
    {
        $otherClass = new SomeOtherClass();
        $otherClass->method();
        $otherClass->property2;
    }
}

?>
