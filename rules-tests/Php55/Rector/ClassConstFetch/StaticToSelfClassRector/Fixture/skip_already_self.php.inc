<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfClassRector\Fixture;

final class SkipAlreadySelf
{
    public function callOnMe()
    {
        var_dump(self::class);
    }
}
