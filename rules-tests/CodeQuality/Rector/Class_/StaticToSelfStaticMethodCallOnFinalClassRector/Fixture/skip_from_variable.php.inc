<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

final class SkipFromVariable
{
    public function execute($static)
    {
        $static::run();
    }

    private function run()
    {
    }
}
