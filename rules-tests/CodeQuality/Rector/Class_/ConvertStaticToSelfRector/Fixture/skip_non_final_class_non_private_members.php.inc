<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\ConvertStaticToSelfRector\Fixture;

class SkipNonFinalClassNonPrivateMembers
{
    public const FOO = 1;

    protected const BAR = 1;

    public static string $foo = 'test';

    protected static string $bar = 'test';

    public static function foo(): void
    {
    }

    protected static function bar(): void
    {
    }

    public function run(): void
    {
        static::FOO;
        static::BAR;
        static::$foo;
        static::$bar;
        static::foo();
        static::bar();
    }
}
