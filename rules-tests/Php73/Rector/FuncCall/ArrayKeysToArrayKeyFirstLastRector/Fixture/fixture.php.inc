<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeysToArrayKeyFirstLastRector\Fixture;

final class SomeClass
{
    public function run(array $items)
    {
        $firstKey = current(array_keys($items));
        $alsoFirstKey = reset(array_keys($items));
        $lastKey = end(array_keys($items));

        return [$firstKey, $alsoFirstKey, $lastKey];
    }
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeysToArrayKeyFirstLastRector\Fixture;

final class SomeClass
{
    public function run(array $items)
    {
        $firstKey = array_key_first($items);
        $alsoFirstKey = array_key_first($items);
        $lastKey = array_key_last($items);

        return [$firstKey, $alsoFirstKey, $lastKey];
    }
}

?>
