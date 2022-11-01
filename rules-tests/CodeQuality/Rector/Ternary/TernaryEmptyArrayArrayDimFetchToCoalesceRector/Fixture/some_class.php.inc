<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector\Fixture;

final class SomeClass
{
    private array $items = [];

    public function run()
    {
        return ! empty($this->items) ? $this->items[0] : 'default';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector\Fixture;

final class SomeClass
{
    private array $items = [];

    public function run()
    {
        return $this->items[0] ?? 'default';
    }
}

?>
