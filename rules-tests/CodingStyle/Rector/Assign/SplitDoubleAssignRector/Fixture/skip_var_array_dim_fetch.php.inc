<?php

namespace Rector\Tests\CodingStyle\Rector\Assign\SplitDoubleAssignRector\Fixture;

use stdClass;

class SkipVarArrayDimFetch
{
    public function run()
    {
        $vouchers[] = $voucher = new stdClass;
    }
}
