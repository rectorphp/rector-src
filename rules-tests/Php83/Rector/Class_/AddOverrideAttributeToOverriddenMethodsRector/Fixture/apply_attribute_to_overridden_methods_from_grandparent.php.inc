<?php

namespace Rector\Tests\Php83\Rector\Class_\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\Class_\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleChildClass;

class ChildClass extends ExampleChildClass
{
    public function foo()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\Class_\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\Class_\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleChildClass;

class ChildClass extends ExampleChildClass
{
    #[\Override]
    public function foo()
    {
    }
}

?>
