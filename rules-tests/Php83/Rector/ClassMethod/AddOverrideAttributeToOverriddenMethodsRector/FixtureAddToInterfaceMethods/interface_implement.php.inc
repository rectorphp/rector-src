<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureAddToInterfaceMethods;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromInterface;

final class InterfaceImplement implements ExampleFromInterface
{
    public function foo()
    {
        return true;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureAddToInterfaceMethods;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\ExampleFromInterface;

final class InterfaceImplement implements ExampleFromInterface
{
    #[\Override]
    public function foo()
    {
        return true;
    }
}

?>
