<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfClassRector\Fixture;

final class SkipCallConstant
{
    private const BAR = 'test';

    public function callOnMe()
    {
        var_dump(static::BAR);
    }
}
