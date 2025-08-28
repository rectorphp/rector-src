<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

class SkipPrivateClone
{
    private function __clone()
    {
    }
}
