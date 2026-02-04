<?php

namespace Rector\Tests\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector\Fixture;

use Rector\Tests\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector\Source\ParentWithPrivateProperty;

final class ChildWithPrivate extends ParentWithPrivateProperty
{
    private string $name;
}
