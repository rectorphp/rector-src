<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Assign\CombinedAssignRector\Fixture;

class skipXorArrayDimFetch
{
    public function run()
    {
        $string = 'Hello world';
        $string[5] = $string[5] ^ '\x06';
        echo $string, "\n";
    }
}

?>