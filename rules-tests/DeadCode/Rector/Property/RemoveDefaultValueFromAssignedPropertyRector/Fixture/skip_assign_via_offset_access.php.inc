<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class SkipAssignViaOffsetAccess
{
    /**
     * @var array<string, bool>
     */
    private array $cachedByName = [];

    public function __construct(string $name)
    {
        $this->cachedByName[$name] = true;
    }
}
