<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class SkipUsedBeforeOverride
{
    public function run()
    {
        return $this->create(1000);
    }

    private function create($value)
    {
        echo $value;

        $value = 500;

        return $value;
    }
}
