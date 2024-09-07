<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector\Fixture;

class SkipOtherVisibility
{
    protected const BAR = 1;

    public const BAZ = 1;

    public function run(): void
    {
        echo static::BAR;
        echo static::BAZ;
    }
}
