<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ConsistentImplodeRector\Fixture;

final class DemoFile
{
    public function run()
    {
        implode('|' . ' ', 'should not be changed?');
    }
}

?>
