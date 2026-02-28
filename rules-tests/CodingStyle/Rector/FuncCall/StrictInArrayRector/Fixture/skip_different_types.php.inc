<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\StrictInArrayRector\Fixture;

class SkipDifferentTypes
{
    public function run()
    {
        $items = ['10', '20', '30'];
        return in_array(10, $items);
    }
}
