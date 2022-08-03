<?php

namespace Rector\Tests\Php80\Rector\Switch_\ChangeSwitchToMatchRector\Fixture;

class SkipDefaultNoStmtNextCase
{
    public function run($value)
    {
        switch ($value) {
            default:
            case 1:
                $a = 1;
                return $a;
        }
    }
}
