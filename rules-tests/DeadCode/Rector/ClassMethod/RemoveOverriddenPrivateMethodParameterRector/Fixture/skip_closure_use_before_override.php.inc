<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class SkipClosureUseBeforeOverride
{
    public function run()
    {
        return $this->create(1000);
    }

    private function create($value)
    {
        $closure = function () use ($value) {
            return $value;
        };

        $value = 500;

        return [$closure, $value];
    }
}
