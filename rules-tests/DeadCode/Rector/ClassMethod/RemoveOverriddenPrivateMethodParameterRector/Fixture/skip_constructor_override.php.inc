<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class SkipConstructorOverride
{
    private $value;

    private function __construct($value)
    {
        $value = 500;

        $this->value = $value;
    }
}
