<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromInterface;

class ApplyAttributeToOverrideMethodFromInterface implements ExampleFromInterface
{
    public function foo()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromInterface;

class ApplyAttributeToOverrideMethodFromInterface implements ExampleFromInterface
{
    #[\Override]
    public function foo()
    {
    }
}

?>
