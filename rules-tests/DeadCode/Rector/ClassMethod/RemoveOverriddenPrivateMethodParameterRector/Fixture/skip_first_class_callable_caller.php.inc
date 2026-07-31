<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipFirstClassCallableCaller extends TestCase
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
