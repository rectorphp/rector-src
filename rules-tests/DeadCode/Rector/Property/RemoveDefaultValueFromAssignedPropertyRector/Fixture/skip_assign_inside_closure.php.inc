<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

use Closure;

final class SkipAssignInsideClosure
{
    private ?string $name = null;

    public Closure $setName;

    public function __construct(string $name)
    {
        $this->setName = function () use ($name): void {
            $this->name = $name;
        };
    }
}
