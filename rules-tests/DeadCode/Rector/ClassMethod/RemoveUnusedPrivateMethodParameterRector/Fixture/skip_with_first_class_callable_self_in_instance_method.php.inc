<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipWithFirstClassCallableSelfInInstanceMethod
{
    public function run()
    {
        return self::validate(...);
    }

    private function validate(string $attribute, mixed $value): bool
    {
        return $value === true;
    }
}
