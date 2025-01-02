<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotionRector\Fixture;

/** Re-assign already error on the first place, no need to check */
class SkipReadonlyPromotion
{
    public function __construct(private \stdClass $std)
    {
    }
}
