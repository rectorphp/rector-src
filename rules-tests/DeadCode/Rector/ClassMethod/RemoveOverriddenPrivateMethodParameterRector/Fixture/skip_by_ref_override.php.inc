<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class SkipByRefOverride
{
    public function run()
    {
        $value = 1000;
        $this->create($value);

        return $value;
    }

    private function create(&$value)
    {
        $value = 500;
    }
}
