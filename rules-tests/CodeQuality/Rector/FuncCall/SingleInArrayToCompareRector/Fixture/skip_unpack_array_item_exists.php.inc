<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector\Fixture;

final class SkipUnpackArrayItemExists
{
    public function run()
    {
        $a = ['a', 'b'];
        echo in_array('a', [...$a]);
    }
}