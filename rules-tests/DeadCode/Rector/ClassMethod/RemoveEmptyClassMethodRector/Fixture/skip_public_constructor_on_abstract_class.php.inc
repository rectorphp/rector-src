<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

abstract class SkipPublicConstructorOnAbstractClass
{
    public function __construct()
    {
    }
}
