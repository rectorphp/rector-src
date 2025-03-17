<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

final class SkipDateTimeAgain
{
    private \DateTime $today;

    public function __construct(\DateTime $today)
    {
        $this->today = $today;
    }
}
