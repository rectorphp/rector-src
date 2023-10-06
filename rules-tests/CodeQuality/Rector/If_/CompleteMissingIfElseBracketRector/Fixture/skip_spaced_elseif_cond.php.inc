<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class SkipSpacedElseIfCond
{
    public function run($value, $value2, $value3)
    {
        if (!$value) {

        } elseif ($value2 &&
            $value3
        ) {
            // just comment
        } else {
            // another comment
        }
    }
}
