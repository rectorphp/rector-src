<?php

namespace Rector\Tests\DowngradePhp73\Rector\FuncCall\DowngradeArrayKeyFirstLastRector\Fixture;

class CastArrayKeyLastValue
{
    public function run($items)
    {
        $lastItemKey = array_key_last((array) $items);
    }
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp73\Rector\FuncCall\DowngradeArrayKeyFirstLastRector\Fixture;

class CastArrayKeyLastValue
{
    public function run($items)
    {
        $arrayItems = (array) $items;
        end($arrayItems);
        $lastItemKey = key($arrayItems);
    }
}

?>
