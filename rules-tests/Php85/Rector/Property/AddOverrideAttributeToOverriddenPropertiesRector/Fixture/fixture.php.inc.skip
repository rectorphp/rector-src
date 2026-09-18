<?php

namespace Rector\Tests\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector\Fixture;

use Rector\Tests\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector\Source\ParentWithPublicProperty;

final class ChildClass extends ParentWithPublicProperty
{
    public string $name;
}

?>
-----
<?php

namespace Rector\Tests\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector\Fixture;

use Rector\Tests\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector\Source\ParentWithPublicProperty;

final class ChildClass extends ParentWithPublicProperty
{
    #[\Override]
    public string $name;
}

?>
