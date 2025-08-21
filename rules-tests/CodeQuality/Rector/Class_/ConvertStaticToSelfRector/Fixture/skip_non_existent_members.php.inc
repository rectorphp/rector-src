<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

final class SkipNonExistentMembers
{
    public function __invoke(): void
    {
        static::FOO;
        static::$bar;
        static::run();
    }
}
