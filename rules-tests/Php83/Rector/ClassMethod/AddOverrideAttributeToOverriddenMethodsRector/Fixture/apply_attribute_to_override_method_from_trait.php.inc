<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTrait;

class ApplyAttributeToOverrideMethodFromTrait
{
    use ExampleFromTrait;

    public function bar()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromTrait;

class ApplyAttributeToOverrideMethodFromTrait
{
    use ExampleFromTrait;

    #[\Override]
    public function bar()
    {
    }
}

?>
