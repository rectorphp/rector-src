<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

// untyped property is handled by RemoveNullPropertyInitializationRector
final class SkipUntypedProperty
{
    private $value = null;

    public function __construct(int $value)
    {
        $this->value = $value;
    }
}
