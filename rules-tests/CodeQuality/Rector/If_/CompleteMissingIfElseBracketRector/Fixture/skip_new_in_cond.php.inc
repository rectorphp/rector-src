<?php

namespace Rector\Tests\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector\Fixture;

class SkipNewInCond
{
    public function execute()
    {
         if ($a !== $b ||
            new \DateTime() > new \DateTime('now')) {
            return;
         }
    }
}