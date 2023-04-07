<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

class IntegerKeys
{
    public function run()
    {
        $iter1 = [0 => 'two', 3 => 'four'];
        $iter2 = [5 => 'six', 7 => 'eight'];

        return array_merge($iter1, $iter2);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

class IntegerKeys
{
    public function run()
    {
        $iter1 = [0 => 'two', 3 => 'four'];
        $iter2 = [5 => 'six', 7 => 'eight'];

        return [...$iter1, ...$iter2];
    }
}

?>
