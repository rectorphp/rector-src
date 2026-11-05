<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipWithFirstClassCallableStatic
{
    public static function run()
    {
        return static::validate(...);
    }

    private static function validate(string $attribute, mixed $value): bool
    {
        return $value === true;
    }
}
