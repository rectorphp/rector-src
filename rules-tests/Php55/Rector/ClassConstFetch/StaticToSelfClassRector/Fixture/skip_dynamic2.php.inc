<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfClassRector\Fixture;

final class SkipDynamic2
{
    public function callOnMe($variable)
    {
        var_dump(static::$variable);
    }
}
