<?php

namespace Rector\Tests\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector\Fixture;

use Rector\Tests\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector\Source\ParentWithPublicProperty;

final class ChildWithAttribute extends ParentWithPublicProperty
{
    #[\Override]
    public string $name;
}
