<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipPublicMethodOverride extends TestCase
{
    public function create($value)
    {
        $value = 500;

        return $value;
    }
}
