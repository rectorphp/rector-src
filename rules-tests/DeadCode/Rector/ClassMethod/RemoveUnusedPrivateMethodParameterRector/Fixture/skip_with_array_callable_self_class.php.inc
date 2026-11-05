<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipWithArrayCallableSelfClass
{
    public static function run()
    {
        return [self::class, 'validate'];
    }

    private static function validate(string $attribute, mixed $value): bool
    {
        return $value === true;
    }
}
