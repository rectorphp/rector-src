<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfClassRector\Fixture;

trait SkipInTrait
{
    public function callOnMe()
    {
        var_dump(static::class);
    }
}
