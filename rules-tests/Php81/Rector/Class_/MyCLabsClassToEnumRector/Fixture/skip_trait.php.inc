<?php

namespace Rector\Tests\Php81\Rector\Class_\MyCLabsClassToEnumRector\Fixture;

use MyCLabs\Enum\Enum;

trait SkipTrait
{
    /**
     * @param Enum
     */
    public function equalsEnum(self $other): bool
    {
        return $other->getValue() === $this->getValue();
    }
}
