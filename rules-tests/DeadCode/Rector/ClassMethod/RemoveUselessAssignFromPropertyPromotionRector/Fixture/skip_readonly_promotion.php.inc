<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUselessAssignFromPropertyPromotionRector\Fixture;

/**
 * Re-assign already error on the first place, no need to check
 * see https://3v4l.org/HGulh#v8.4.2
 */
class SkipReadonlyPromotion
{
    public function __construct(private \stdClass $std)
    {
    }
}
