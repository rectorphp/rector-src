<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Fixture;

final class SkipAlreadySelf
{
    private const BAR = 1;

    public function baz(): void
    {
        echo self::BAR;
    }
}
