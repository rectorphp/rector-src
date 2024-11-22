<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

class SkipUsedByGetObjectVarsFromJsonSerializable implements \JsonSerializable
{
    public function __construct(private readonly string $foo)
    {
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
