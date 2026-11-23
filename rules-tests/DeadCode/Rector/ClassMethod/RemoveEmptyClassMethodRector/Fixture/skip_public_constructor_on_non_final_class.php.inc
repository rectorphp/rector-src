<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

/**
 * child class can call parent::__construct()
 */
class SkipPublicConstructorOnNonFinalClass
{
    public function __construct()
    {
    }
}
