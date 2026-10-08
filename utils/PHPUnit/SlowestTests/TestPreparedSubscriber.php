<?php

declare(strict_types=1);

namespace Rector\Utils\PHPUnit\SlowestTests;

use PHPUnit\Event\Test\Prepared;
use PHPUnit\Event\Test\PreparedSubscriber;

final readonly class TestPreparedSubscriber implements PreparedSubscriber
{
    public function __construct(
        private SlowTestReporter $slowTestReporter
    ) {
    }

    public function notify(Prepared $event): void
    {
        $this->slowTestReporter->testPrepared(
            $event->test()->id(),
            $event->telemetryInfo()->durationSinceStart()->asFloat()
        );
    }
}
