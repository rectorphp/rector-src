<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class SkipDirectSemicolon
{
    public function run($value)
    {
        if ($value);

        echo 'something';
    }
}