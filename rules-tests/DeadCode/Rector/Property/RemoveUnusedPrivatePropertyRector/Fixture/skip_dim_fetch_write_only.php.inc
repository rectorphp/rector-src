<?php

namespace Rector\Tests\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector\Fixture;

final class SkipDimFetchWriteOnly
{
    private $seemsUnused;

    public function getValue()
    {
        foreach ([1, 2, 3] as $key) {
            $this->seemsUnused[] = 'foo';
        }
    }
}
