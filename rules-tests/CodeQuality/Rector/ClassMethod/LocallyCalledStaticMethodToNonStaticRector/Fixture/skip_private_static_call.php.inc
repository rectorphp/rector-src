<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

final class SkipPrivateStaticCall
{
    public static function clear(string $input)
    {
        return self::reallyClear($input);
    }

    private static function reallyClear($input)
    {
        return $input . ' - clean';
    }
}
