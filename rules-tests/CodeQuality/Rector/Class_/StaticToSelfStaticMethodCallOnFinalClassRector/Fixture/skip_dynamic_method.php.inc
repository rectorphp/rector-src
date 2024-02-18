<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\StaticToSelfStaticMethodCallOnFinalClassRector\Fixture;

final class SkipDynamicMethod
{
    public function execute($execute)
    {
        static::{$execute}();
    }
}
