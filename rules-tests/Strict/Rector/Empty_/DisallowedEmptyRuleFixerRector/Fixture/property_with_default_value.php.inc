<?php

namespace Rector\Tests\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector\Fixture;

final class PropertyWithDefaultValue
{
    public array $items = [];

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

final class PropertyWithDefaultValue
{
    public array $items = [];

    public function isEmpty()
    {
        return $this->items === [];
    }

    public function isNotEmpty()
    {
        return $this->items !== [];
    }
}

?>
