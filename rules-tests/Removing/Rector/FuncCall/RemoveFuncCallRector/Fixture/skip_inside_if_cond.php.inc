<?php

namespace Rector\Tests\Removing\Rector\FuncCall\RemoveFuncCallRector\Fixture;

class SkipInsideIfCond
{
    public function run()
    {
        if (var_dump('something')) {
            return true;
        }

        return false;
    }
}

?>

