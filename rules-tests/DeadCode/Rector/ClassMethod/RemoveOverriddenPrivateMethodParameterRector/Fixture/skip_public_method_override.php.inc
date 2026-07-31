<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class SkipPublicMethodOverride
{
    public function create($value)
    {
        $value = 500;

        return $value;
    }
}
