<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

final class SkipCallNonExistenceMethod
{
    public function execute($execute)
    {
        static::run();
    }
}
