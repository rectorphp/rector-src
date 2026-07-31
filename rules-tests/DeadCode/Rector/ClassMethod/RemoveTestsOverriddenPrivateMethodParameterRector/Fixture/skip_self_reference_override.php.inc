<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveTestsOverriddenPrivateMethodParameterRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipSelfReferenceOverride extends TestCase
{
    public function run()
    {
        return $this->create(1000);
    }

    private function create($value)
    {
        $value = $value ?: 500;

        return $value;
    }
}
