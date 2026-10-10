<?php

declare(strict_types=1);

namespace Rector\Utils\PHPUnit\SlowestTests;

use PHPUnit\Event\Code\TestMethod;
use PHPUnit\Event\Test\Finished;
use PHPUnit\Event\Test\FinishedSubscriber;

final readonly class TestFinishedSubscriber implements FinishedSubscriber
{
    public function __construct(
        private SlowTestReporter $slowTestReporter
    ) {
    }

    public function notify(Finished $event): void
    {
        $test = $event->test();

        // group all data-provider fixtures under one test method, so a slow rule shows as a single entry
        $reportName = $test instanceof TestMethod ? $test->className() . '::' . $test->methodName() : $test->name();

        $this->slowTestReporter->testFinished(
            $test->id(),
            $reportName,
            $event->telemetryInfo()->durationSinceStart()->asFloat()
        );
    }
}
