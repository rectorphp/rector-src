<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class SkipSpacedElseStmts
{
    public function run($value, $value2)
    {
        if ($value) {
            return 1;
        } else {
            $a = 1;

            return $a;
        }
    }
}
