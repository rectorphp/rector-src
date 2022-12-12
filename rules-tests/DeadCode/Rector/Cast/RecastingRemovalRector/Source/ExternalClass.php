<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Source;

final class ExternalClass
{
    /**
     * @return string
     */
    public function getResult()
    {
        return 1;
    }
}
