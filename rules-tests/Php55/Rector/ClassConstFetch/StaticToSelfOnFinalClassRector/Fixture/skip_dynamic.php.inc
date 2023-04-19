<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfOnFinalClassRector\Fixture;

final class SkipDynamic
{
    public function callOnMe($variable)
    {
        var_dump($variable::class);
    }
}
