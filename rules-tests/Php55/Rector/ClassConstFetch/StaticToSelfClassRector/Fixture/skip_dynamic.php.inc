<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\StaticToSelfClassRector\Fixture;

final class SkipDynamic
{
    public function callOnMe($variable)
    {
        var_dump($variable::class);
    }
}
