<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class NegatedUnionType
{
    /**
     * @param int|int[]|string $value
     */
    public function run($value)
    {
        return ! empty($value);
    }
}

?>
-----
<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class NegatedUnionType
{
    /**
     * @param int|int[]|string $value
     */
    public function run($value)
    {
        return $value !== 0 && $value !== '' && $value !== [];
    }
}

?>
