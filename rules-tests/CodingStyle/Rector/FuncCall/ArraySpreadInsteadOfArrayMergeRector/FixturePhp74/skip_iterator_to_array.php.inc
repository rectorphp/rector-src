<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

final class SkipIteratorToArray
{
    public function run($items)
    {
        $values = iterator_to_array($items);
    }
}

?>
