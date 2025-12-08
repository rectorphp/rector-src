<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\ArrowFunctionToFirstClassCallableRector\Fixture;

class SkipUsingThisOutsideObject
{
    public static function boot(): void
    {
        self::macro('foo', fn () => $this->values());
        self::macro('foo', fn () => $this->values()->foo());
    }

    public static function macro(string $name, callable $callback): void
    {
    }
}
