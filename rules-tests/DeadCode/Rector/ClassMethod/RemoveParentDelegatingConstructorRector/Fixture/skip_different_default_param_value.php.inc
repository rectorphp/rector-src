<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

class SkipWithDefaultParams extends \DateTime
{
    public function __construct(string $datetime = "tomorrow", ?\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
    }
}
