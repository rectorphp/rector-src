<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

class SkipDocblockBasedTypeArrayMerge
{
    /**
     * @param array<int, string> $iter1
     * @param array<int, string> $iter2
     */
    public function run($iter1, $iter2)
    {
        $values = array_merge($iter1, $iter2);
    }
}
