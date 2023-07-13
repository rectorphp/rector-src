<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

class SkipNonTypedParam
{
    /**
     * @param bool $isStrict
     */
    public function run($isStrict = true)
    {
        $value = (bool) $isStrict;
    }
}

?>
