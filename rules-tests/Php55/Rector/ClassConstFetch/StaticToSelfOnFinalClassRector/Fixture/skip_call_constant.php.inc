<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector\Fixture;

final class SkipCallConstant
{
    private const BAR = 'test';

    public function callOnMe()
    {
        var_dump(static::BAR);
    }
}
