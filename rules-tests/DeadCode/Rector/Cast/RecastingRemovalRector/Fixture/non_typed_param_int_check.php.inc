<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

class NonTypedParamIsIntCheck
{
    /**
     * @param int|array $flags
     */
    public function run($flags)
    {
        if (is_int($flags)) {
            $flags = (int) $flags;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

class NonTypedParamIsIntCheck
{
    /**
     * @param int|array $flags
     */
    public function run($flags)
    {
        if (is_int($flags)) {
            $flags = $flags;
        }
    }
}

?>