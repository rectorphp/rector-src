<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector\Fixture;

final class SkipPrivateStaticCallUsingClassNameFromStatic
{
    public static function bar(string $input)
    {
        return SkipPrivateStaticCallUsingClassNameFromStatic::reallyBar($input);
    }

    private static function reallyBar($input)
    {
        return $input . ' - bar';
    }
}
