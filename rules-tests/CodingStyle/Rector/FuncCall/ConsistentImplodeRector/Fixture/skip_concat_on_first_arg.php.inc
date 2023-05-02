<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\ConsistentImplodeRector\Fixture;

final class SkipConcatOnFirstArg
{
    public function run()
    {
        implode('|' . ' ', 'should not be changed');
    }
}

?>
