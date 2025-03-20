<?php

namespace Rector\Tests\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector\Fixture;

class SkipVersionCompare
{
    public function run()
    {
        version_compare($a, $b, $c);
        version_compare(1, 2);
        version_compare(PHP_VERSION, '5.6', 'le');
    }
}

?>
