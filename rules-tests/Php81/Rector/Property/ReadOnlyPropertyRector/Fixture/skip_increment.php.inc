<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipIncrement
{
    public function __construct(private int $maxRetriesLeft)
    {
    }

    private function retry(): void
    {
        $this->maxRetriesLeft--;
    }
}


