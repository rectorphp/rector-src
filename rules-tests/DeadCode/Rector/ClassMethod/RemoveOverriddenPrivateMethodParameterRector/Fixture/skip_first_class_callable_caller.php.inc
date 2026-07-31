<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class SkipFirstClassCallableCaller
{
    public function run()
    {
        return $this->create(...);
    }

    private function create($value)
    {
        $value = 500;

        return $value;
    }
}
