<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

final class SkipNonStaticCall
{
    public function execute()
    {
        $this->run();
    }

    private function run()
    {
    }
}
