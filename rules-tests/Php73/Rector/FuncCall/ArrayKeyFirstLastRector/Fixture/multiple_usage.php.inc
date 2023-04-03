<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeyFirstLastRector\Fixture;

class MultipleUsage
{
    public function getFirstKey($items, $items2)
    {
        reset($items);
        echo key($items);

        reset($items2);
        echo key($items2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php73\Rector\FuncCall\ArrayKeyFirstLastRector\Fixture;

class MultipleUsage
{
    public function getFirstKey($items, $items2)
    {
        echo array_key_first($items);
        echo array_key_first($items2);
    }
}

?>
