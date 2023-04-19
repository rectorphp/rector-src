<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector\Fixture;

final class SkipAlreadySelf
{
    public function callOnMe()
    {
        var_dump(self::class);
    }
}
