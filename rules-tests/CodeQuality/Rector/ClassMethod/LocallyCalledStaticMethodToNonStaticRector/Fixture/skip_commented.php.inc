<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

final class SkipCommented
{
    public static function run()
    {
        $result = self::anotherStatic();

        // $result = self::someStatic();
    }

    private static function anotherStatic(): array
    {
        return [];
    }

    private static function someStatic(): array
    {
        return [];
    }
}
