<?php

namespace Rector\Tests\Php55\Rector\ClassConstFetch\ConvertStaticToSelfRector\Fixture;

abstract class SkipAbstractClass
{
    protected const BAR = 1;

    protected static string $foo = 'test';

    protected static function baz(): void
    {
    }

    public function run()
    {
        static::$foo;
        static::baz();
        static::BAR;
    }
}
