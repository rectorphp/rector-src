<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

class SkipNonTypedParamNumericCheck
{
    /**
     * @param int|array $flags
     */
    public function run($flags)
    {
        if (is_numeric($flags)) {
            $flags = (int) $flags;
        }
    }
}
