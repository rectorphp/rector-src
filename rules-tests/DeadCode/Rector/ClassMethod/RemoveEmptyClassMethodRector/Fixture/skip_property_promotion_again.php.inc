<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

class SkipPropertyPromotion
{
    public function __construct(private int $age)
    {
    }
}
