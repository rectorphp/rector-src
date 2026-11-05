<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipWithArrayCallableDynamicMethod
{
    public function run(string $method)
    {
        return [$this, $method];
    }

    private function validate(string $attribute, mixed $value): bool
    {
        return $value === true;
    }
}
