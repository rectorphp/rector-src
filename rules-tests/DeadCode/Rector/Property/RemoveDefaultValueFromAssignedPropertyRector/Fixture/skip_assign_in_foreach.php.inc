<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector\Fixture;

final class SkipAssignInForeach
{
    private ?string $name = null;

    /**
     * @param string[] $names
     */
    public function __construct(array $names)
    {
        foreach ($names as $name) {
            $this->name = $name;
        }
    }
}
