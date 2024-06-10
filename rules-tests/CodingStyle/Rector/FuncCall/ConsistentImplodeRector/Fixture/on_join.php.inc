<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ConsistentImplodeRector\Fixture;

class OnJoin
{
    public function run(array $items)
    {
        $itemsAsStrings = join($items);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ConsistentImplodeRector\Fixture;

class OnJoin
{
    public function run(array $items)
    {
        $itemsAsStrings = join('', $items);
    }
}

?>
