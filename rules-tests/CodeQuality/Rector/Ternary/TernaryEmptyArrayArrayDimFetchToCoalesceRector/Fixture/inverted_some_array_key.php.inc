<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector\Fixture;

final class InvertedSomeArrayKey
{
    private $items;

    public function run()
    {
        return empty($this->items[0]) ? 'default' : $this->items[0];
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector\Fixture;

final class InvertedSomeArrayKey
{
    private $items;

    public function run()
    {
        return $this->items[0] ?? 'default';
    }
}

?>
