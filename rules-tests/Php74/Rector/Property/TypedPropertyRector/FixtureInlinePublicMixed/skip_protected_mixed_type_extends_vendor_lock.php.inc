<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureInlinePublicMixed;

use Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Source\SomeParent;

final class SkipProtectedMixedTypeExtendsVendorLock extends SomeParent
{
    /**
     * @var mixed
     */
    protected $property;
}
