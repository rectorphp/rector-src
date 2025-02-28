<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipPropertyHook
{
    private int $propertyHook {
        get => random_int(1, 100);
        set => 1;
    }

    public function bar(): int
    {
        return $this->propertyHook * 1000;
    }
}
