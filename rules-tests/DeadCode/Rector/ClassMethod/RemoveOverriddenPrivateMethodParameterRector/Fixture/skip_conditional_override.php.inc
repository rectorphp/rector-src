<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class SkipConditionalOverride
{
    public function run()
    {
        return $this->create(1000);
    }

    private function create($value)
    {
        if (rand(0, 1)) {
            $value = 500;
        }

        return $value;
    }
}
