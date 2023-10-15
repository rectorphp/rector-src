<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleChildClass;

class ChildClass extends ExampleChildClass
{
    public function foo()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleChildClass;

class ChildClass extends ExampleChildClass
{
    #[\Override]
    public function foo()
    {
    }
}

?>
