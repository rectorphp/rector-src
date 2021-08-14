<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

class TypedParam
{
    /**
     * @param bool $isStrict
     */
    public function run(bool $isStrict = true)
    {
        $value = (bool) $isStrict;
    }
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Rector;

class TypedParam
{
    /**
     * @param bool $isStrict
     */
    public function run(bool $isStrict = true)
    {
        $value = $isStrict;
    }
}

?>