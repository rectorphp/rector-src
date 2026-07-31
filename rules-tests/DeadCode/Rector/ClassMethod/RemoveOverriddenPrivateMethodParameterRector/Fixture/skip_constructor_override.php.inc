<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

use PHPUnit\Framework\TestCase;

final class SkipConstructorOverride extends TestCase
{
    private $value;

    private function __construct($value)
    {
        $value = 500;

        $this->value = $value;
    }
}
