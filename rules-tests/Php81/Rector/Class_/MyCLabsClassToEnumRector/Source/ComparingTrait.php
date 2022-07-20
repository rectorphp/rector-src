<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\Class_\MyCLabsClassToEnumRector\Source;

use MyCLabs\Enum\Enum;

trait ComparingTrait
{
    /**
     * @param Enum
     */
    public function equalsEnum(self $other): bool
    {
        return $other->getValue() === $this->getValue();
    }
}
