<?php

namespace Rector\Tests\Php84\Rector\Class_\PropertyHookRector\Fixture;

final class SkipReadonlyPromotedProperty
{
    public function __construct(
        private readonly int $value,
    )
    {
    }

    public function getValue()
    {
        return $this->value;
    }
}
