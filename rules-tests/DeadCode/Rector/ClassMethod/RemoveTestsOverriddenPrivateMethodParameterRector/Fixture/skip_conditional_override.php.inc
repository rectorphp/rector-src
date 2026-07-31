<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveTestsOverriddenPrivateMethodParameterRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipConditionalOverride extends TestCase
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
