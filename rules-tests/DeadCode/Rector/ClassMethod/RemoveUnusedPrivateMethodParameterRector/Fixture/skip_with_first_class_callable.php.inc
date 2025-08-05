<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector\Fixture;

final class SkipWithFirstClassCallable
{
    public function run()
    {
        return $this->validate(...);
    }

    private function validate(string $attribute, mixed $value): bool
    {
        return $value === true;
    }
}
