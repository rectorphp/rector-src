<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\ValueObject;

use Rector\Core\Exception\ShouldNotHappenException;

final class StartAndEnd
{
    public function __construct(
        private readonly int $start,
        private readonly int $end
    ) {
        if ($end < $start) {
            throw new ShouldNotHappenException();
        }
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getEnd(): int
    {
        return $this->end;
    }
}
