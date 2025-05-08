<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeyFirstLastRector\Fixture;

class KeyCalledMultipleTimes
{
    public function getFirstKey($items)
    {
        reset($items);
        echo key($items);
        echo key($items);
    }

    public function getLastKey($items2)
    {
        end($items2);
        echo key($items2);
        echo key($items2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeyFirstLastRector\Fixture;

class KeyCalledMultipleTimes
{
    public function getFirstKey($items)
    {
        echo array_key_first($items);
        echo array_key_first($items);
    }

    public function getLastKey($items2)
    {
        echo array_key_last($items2);
        echo array_key_last($items2);
    }
}

?>
