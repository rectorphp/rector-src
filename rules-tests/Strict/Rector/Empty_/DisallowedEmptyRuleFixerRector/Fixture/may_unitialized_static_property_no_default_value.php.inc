<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class MayUnitializedStaticPropertyNoDefaultValue
{
    public static array $items;

    public function isEmpty()
    {
        return empty(self::$items);
    }

    public function isNotEmpty()
    {
        return ! empty(self::$items);
    }
}

?>
-----
<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class MayUnitializedStaticPropertyNoDefaultValue
{
    public static array $items;

    public function isEmpty()
    {
        return !isset(self::$items) || self::$items === [];
    }

    public function isNotEmpty()
    {
        return isset(self::$items) && self::$items !== [];
    }
}

?>
