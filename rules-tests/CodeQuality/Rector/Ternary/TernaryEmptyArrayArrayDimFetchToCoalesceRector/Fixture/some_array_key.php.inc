<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector\Fixture;

final class SomeArrayKey
{
    private $items;

    public function run()
    {
        return ! empty($this->items[0]) ? $this->items[0] : 'default';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector\Fixture;

final class SomeArrayKey
{
    private $items;

    public function run()
    {
        return $this->items[0] ?: 'default';
    }
}

?>
