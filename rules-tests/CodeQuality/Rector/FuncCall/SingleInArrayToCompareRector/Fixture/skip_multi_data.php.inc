<?php

namespace Rector\Tests\CodeQuality\Rector\FuncCall\SingleInArrayToCompareRector\Fixture;

class SkipMultiData
{
    public function run()
    {
        $isIt = in_array(strtolower($type), ['$this', 'two']);
    }
}
