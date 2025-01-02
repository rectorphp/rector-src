<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotion\Fixture;

class SkipNormalParam
{
    private \stdClass $std;

    public function __construct(\stdClass $std)
    {
        $this->std = $std;
    }
}

