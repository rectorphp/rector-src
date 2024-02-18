<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

class SkipNonFinal
{
    public function execute()
    {
        static::run();
    }

    private static function run()
    {
    }
}
