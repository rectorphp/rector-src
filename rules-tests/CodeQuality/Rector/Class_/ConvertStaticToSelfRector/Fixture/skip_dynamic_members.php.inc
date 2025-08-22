<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

final class SkipDynamicMembers
{
    public function run($name): void
    {
        static::{$name}();
        static::$$name;
        static::$name;
    }
}
