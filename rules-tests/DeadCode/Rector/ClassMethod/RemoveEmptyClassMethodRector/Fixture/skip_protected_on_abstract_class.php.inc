<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

abstract class SkipProtectedOnAbstractClass
{
    protected function __construct()
    {
    }
}
