<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

class SkipProtectedOnNonFinalClass
{
    protected function __construct()
    {
    }
}
