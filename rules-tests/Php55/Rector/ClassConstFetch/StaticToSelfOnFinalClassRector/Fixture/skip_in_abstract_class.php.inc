<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector\Fixture;

abstract class SkipInAbstractClass
{
    public function callOnMe()
    {
        var_dump(static::class);
    }
}
