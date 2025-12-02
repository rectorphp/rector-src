<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SetTypeToCastRector\Fixture;

final class SkipInvalidCastType
{
    public function run($foo)
    {
        is_bool(settype($foo, 'super-element'));
    }
}
