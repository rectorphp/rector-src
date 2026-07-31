<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveTestsOverriddenPrivateMethodParameterRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipByRefOverride extends TestCase
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
