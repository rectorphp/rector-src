<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotionRector\Fixture;

/**
 * Re-assign readonly promotion already error on the first place, no need to check
 * see https://3v4l.org/HGulh#v8.4.2
 */
class SkipReadonlyPromotion
{
    public function __construct(private readonly \stdClass $std)
    {
    }
}
