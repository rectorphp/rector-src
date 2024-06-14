<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

final class SkipVariableUsedNextStmt
{
    private \DateTime $time;
    private bool $timeWasSet;

    public function __construct(?\DateTime $time = null)
    {
        $this->time = $time ?? new \DateTime();
        $this->timeWasSet = null !== $time;
    }
}
