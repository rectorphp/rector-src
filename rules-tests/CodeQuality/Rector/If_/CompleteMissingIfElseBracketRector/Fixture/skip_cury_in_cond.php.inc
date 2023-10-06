<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class SkipCurlyInCond
{
    public function execute()
    {
         if ($a !== $b ||

            (function() {})()

            ) {
            return;
         }
    }
}