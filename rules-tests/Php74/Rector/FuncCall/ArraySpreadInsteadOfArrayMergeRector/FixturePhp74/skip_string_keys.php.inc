<?php

namespace Rector\Tests\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector\FixturePhp74;

class SkipStringKeys
{
    public function run()
    {
        $iter1 = ['one' => 'two', 'three' => 'four'];
        $iter2 = ['five' => 'six', 'seven' => 'eight'];

        return array_merge($iter1, $iter2);
    }

    public function go()
    {
        $iter1 = [1 => 'two', 3 => 'four'];
        $iter2 = ['five' => 'six', 'seven' => 'eight'];

        return array_merge($iter1, $iter2);
    }
}
