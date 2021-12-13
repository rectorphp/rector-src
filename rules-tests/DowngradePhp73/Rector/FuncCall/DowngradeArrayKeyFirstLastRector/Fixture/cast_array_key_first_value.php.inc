<?php

namespace Rector\Tests\DowngradePhp73\Rector\FuncCall\DowngradeArrayKeyFirstLastRector\Fixture;

class CastArrayKeyFirstValue
{
    public function run($items)
    {
        $firstItemKey = array_key_first((array) $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp73\Rector\FuncCall\DowngradeArrayKeyFirstLastRector\Fixture;

class CastArrayKeyFirstValue
{
    public function run($items)
    {
        $arrayItem = (array) $items;
        reset($arrayItem);
        $firstItemKey = key($arrayItem);
    }
}

?>
