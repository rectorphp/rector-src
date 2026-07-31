<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeysToArrayKeyFirstLastRector\Fixture;

final class NestedExpression
{
    public function run(array $items)
    {
        return trim((string) current(array_keys($this->resolveItems($items))));
    }

    private function resolveItems(array $items): array
    {
        return $items;
    }
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeysToArrayKeyFirstLastRector\Fixture;

final class NestedExpression
{
    public function run(array $items)
    {
        return trim((string) array_key_first($this->resolveItems($items)));
    }

    private function resolveItems(array $items): array
    {
        return $items;
    }
}

?>
