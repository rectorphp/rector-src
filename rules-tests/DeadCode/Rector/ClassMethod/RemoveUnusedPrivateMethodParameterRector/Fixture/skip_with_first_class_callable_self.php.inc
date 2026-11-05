<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipWithFirstClassCallableSelf
{
    public static function run()
    {
        return self::validate(...);
    }

    private static function validate(string $attribute, mixed $value): bool
    {
        return $value === true;
    }
}
