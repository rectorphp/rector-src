<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromAnotherInterface;
use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleParentClass;

final class SkipInterfaceMixedUsage extends ExampleParentClass implements ExampleFromAnotherInterface
{
    public function foo()
    {
        return 'overrides method';
    }

    public function baz()
    {
        return 'implements only';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Fixture;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromAnotherInterface;
use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleParentClass;

final class SkipInterfaceMixedUsage extends ExampleParentClass implements ExampleFromAnotherInterface
{
    #[\Override]
    public function foo()
    {
        return 'overrides method';
    }

    public function baz()
    {
        return 'implements only';
    }
}

?>
