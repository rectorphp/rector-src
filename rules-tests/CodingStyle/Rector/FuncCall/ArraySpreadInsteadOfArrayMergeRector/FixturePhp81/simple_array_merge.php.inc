<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

class SimpleArrayMerge
{
    public function run($iter1, $iter2)
    {
        $values = array_merge($iter1, $iter2);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp81;

class SimpleArrayMerge
{
    public function run($iter1, $iter2)
    {
        $values = [...$iter1, ...$iter2];
    }
}

?>
