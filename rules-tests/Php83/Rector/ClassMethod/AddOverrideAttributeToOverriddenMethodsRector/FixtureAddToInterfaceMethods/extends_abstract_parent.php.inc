<?php

declare(strict_types=1);

namespace Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\FixtureAddToInterfaceMethods;

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\AbstractParentWithInterface;

final class ExtendsAbstractParent extends AbstractParentWithInterface
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

use Rector\Tests\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector\Source\AbstractParentWithInterface;

final class ExtendsAbstractParent extends AbstractParentWithInterface
{
    #[\Override]
    public function foo()
    {
        return true;
    }
}

?>
