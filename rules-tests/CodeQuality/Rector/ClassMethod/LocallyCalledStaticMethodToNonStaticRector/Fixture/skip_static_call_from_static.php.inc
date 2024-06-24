<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

final class SkipPrivateStaticCallFromStatic
{
    public static function clear(string $input)
    {
        return static::reallyClear($input);
    }

    public static function foo(string $input)
    {
        return self::reallyFoo($input);
    }

    public static function bar(string $input)
    {
        return SkipPrivateStaticCallFromStatic::reallyBar($input);
    }

    private static function reallyClear($input)
    {
        return $input . ' - clean';
    }

    private static function reallyFoo($input)
    {
        return $input . ' - foo';
    }

    private static function reallyBar($input)
    {
        return $input . ' - bar';
    }
}
