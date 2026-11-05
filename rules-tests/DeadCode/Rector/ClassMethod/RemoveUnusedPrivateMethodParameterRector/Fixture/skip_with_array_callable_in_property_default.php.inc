<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipWithArrayCallableInPropertyDefault
{
    public array $callback = [self::class, 'validate'];

    private static function validate(string $attribute, mixed $value): bool
    {
        return $value === true;
    }
}
