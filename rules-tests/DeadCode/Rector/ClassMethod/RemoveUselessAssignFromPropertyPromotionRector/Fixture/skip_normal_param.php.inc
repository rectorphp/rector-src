<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotionRector\Fixture;

class SkipNormalParam
{
    private \stdClass $std;

    public function __construct(\stdClass $std)
    {
        $this->std = $std;
    }
}

