<?php

namespace Rector\Tests\Php80\Rector\FuncCall\ConsistentImplodeRector\Fixture;

final class SkipConcatOnFirstArg
{
    public function run()
    {
        implode('|' . ' ', 'should not be changed');
    }
}

?>
