<?php

namespace Rector\Tests\Php81\Rector\ClassMethod\NewInInitializerRector\Fixture;

final class SkipWithNextRequired
{
    private \DateTime $time;

    public function __construct(?\DateTime $time, int $required)
    {
        $this->time = $time ?? new \DateTime();
    }
}
