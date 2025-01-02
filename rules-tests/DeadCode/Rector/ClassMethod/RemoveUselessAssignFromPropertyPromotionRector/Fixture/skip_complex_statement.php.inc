<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotion\Fixture;

class SkipComplexStatement
{
    public function __construct(private readonly \stdClass $std)
    {
        if (rand(0, 1)) {
            $this->std = '';
        }
    }
}

