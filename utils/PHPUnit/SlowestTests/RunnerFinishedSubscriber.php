<?php

declare(strict_types=1);

namespace Rector\Utils\PHPUnit\SlowestTests;

use PHPUnit\Event\TestRunner\ExecutionFinished;
use PHPUnit\Event\TestRunner\ExecutionFinishedSubscriber;

final readonly class RunnerFinishedSubscriber implements ExecutionFinishedSubscriber
{
    public function __construct(
        private SlowTestReporter $slowTestReporter
    ) {
    }

    public function notify(ExecutionFinished $event): void
    {
        $this->slowTestReporter->printSlowest();
    }
}
