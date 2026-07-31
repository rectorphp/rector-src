<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveTestsOverriddenPrivateMethodParameterRector\Fixture;

final class SkipNonTestClass
{
    public function run()
    {
        return $this->create(1000);
    }

    private function create($value)
    {
        $value = 500;

        return $value;
    }
}
