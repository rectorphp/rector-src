<?php

namespace Rector\Tests\Php83\Rector\Class_\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\Class_\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleParentClass;

class ChildClass extends ExampleParentClass
{
    public function foo()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\Class_\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\Class_\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleParentClass;

class ChildClass extends ExampleParentClass
{
    #[\Override]
    public function foo()
    {
    }
}

?>
