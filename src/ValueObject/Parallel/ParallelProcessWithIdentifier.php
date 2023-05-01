<?php

declare(strict_types=1);

namespace Rector\Core\ValueObject\Parallel;

use Symplify\EasyParallel\ValueObject\ParallelProcess;

final class ParallelProcessWithIdentifier
{
    public function __construct(
        private readonly string $identifier,
        private readonly ParallelProcess $parallelProcess,
    ) {
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getParallelProcess(): ParallelProcess
    {
        return $this->parallelProcess;
    }
}
