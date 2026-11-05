<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipWithArrayCallableStaticClass
{
    public static function run()
    {
        return [static::class, 'validate'];
    }

    private static function validate(string $attribute, mixed $value): bool
    {
        return $value === true;
    }
}
