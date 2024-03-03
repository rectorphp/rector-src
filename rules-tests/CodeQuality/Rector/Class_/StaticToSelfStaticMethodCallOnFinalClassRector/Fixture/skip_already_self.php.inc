<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

final class SkipAlreadySelf
{
    public function execute()
    {
        self::run();
    }

    private static function run()
    {
    }
}
