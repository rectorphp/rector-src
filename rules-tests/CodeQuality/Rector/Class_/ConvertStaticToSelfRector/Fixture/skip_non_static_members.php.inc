<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

final class SkipNonStaticMembers
{
    private string $someProperty = 'value';

    private function run(): void
    {
    }

    public function execute()
    {
        static::run();
        static::$someProperty;
    }
}
