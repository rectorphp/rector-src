<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureInlinePublicMixed;

abstract class SomeParentString
{
    /**
     * @var string
     */
    protected $property;
}

final class ProtectedMixedTypeExtendsChanged extends SomeParentString
{
    /**
     * @var mixed
     */
    protected $property;
}