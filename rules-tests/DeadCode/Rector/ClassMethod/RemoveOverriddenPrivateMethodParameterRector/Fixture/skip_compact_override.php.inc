<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class SkipCompactOverride
{
    public function run()
    {
        return $this->create(1000);
    }

    private function create($value)
    {
        $data = compact('value');

        $value = 500;

        return [$data, $value];
    }
}
