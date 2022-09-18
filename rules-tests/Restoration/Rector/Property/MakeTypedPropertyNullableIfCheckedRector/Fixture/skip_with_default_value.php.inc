<?php

namespace Rector\Tests\Restoration\Rector\Property\MakeTypedPropertyNullableIfCheckedRector\Fixture;

final class SkipWithDefaultValue
{
    private bool $isDebugMode = false;

    public function run()
    {
        if (!$this->isDebugMode) {
            $this->isDebugMode = true;
        }
    }
}
