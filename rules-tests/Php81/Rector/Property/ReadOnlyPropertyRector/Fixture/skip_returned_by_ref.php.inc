<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipReturnedByRef
{
    public function __construct(
        private array $byLine = [],
    ) {
    }

    public function &getByLine(): array
    {
        return $this->byLine;
    }
}
