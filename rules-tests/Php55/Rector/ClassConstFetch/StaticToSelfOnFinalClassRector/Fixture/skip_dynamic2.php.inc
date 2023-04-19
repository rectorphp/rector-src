<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector\Fixture;

final class SkipDynamic2
{
    public function callOnMe($variable)
    {
        var_dump(static::$variable);
    }
}
