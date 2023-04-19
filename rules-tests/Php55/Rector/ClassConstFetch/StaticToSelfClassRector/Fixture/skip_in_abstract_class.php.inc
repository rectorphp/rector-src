<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfClassRector\Fixture;

abstract class SkipInAbstractClass
{
    public function callOnMe()
    {
        var_dump(static::class);
    }
}
