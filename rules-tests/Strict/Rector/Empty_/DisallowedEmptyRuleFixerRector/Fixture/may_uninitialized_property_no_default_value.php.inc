<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class MayUninitializedPropertyNoDefaultValue
{
    public array $items;

    public function isEmpty()
    {
        return empty($this->items);
    }

    public function isNotEmpty()
    {
        return ! empty($this->items);
    }
}

?>
-----
<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class MayUninitializedPropertyNoDefaultValue
{
    public array $items;

    public function isEmpty()
    {
        return !isset($this->items) || $this->items === [];
    }

    public function isNotEmpty()
    {
        return isset($this->items) && $this->items !== [];
    }
}

?>
