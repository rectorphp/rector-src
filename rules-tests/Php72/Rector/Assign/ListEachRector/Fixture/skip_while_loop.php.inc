<?php

namespace Rector\Tests\Php72\Rector\Assign\ListEachRector\Fixture;

final class SkipWhileLoop
{
    public function run($opt)
    {
        while (list($key, $val) = each($opt->option)) {
            return false;
        }

        return true;
    }
}
