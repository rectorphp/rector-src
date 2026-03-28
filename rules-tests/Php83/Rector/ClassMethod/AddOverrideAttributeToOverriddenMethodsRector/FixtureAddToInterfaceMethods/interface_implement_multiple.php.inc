<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureAddToInterfaceMethods;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromAnotherInterface;
use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromInterface;

final class InterfaceImplementMultiple implements ExampleFromInterface, ExampleFromAnotherInterface
{
    public function foo()
    {
        return true;
    }

    public function baz()
    {
        return true;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureAddToInterfaceMethods;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromAnotherInterface;
use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromInterface;

final class InterfaceImplementMultiple implements ExampleFromInterface, ExampleFromAnotherInterface
{
    #[\Override]
    public function foo()
    {
        return true;
    }

    #[\Override]
    public function baz()
    {
        return true;
    }
}

?>
