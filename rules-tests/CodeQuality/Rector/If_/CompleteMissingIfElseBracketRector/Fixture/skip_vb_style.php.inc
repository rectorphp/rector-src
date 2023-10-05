<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class SkipVbStyle
{
    public function run($value)
    {
        if ($value):
            return 1;
        endif;
    }
}
