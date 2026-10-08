<?php

declare(strict_types=1);

namespace Rector\Utils\PHPUnit\SlowestTests;

use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

final class SlowTestExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $slowTestReporter = new SlowTestReporter();

        $facade->registerSubscribers(
            new TestPreparedSubscriber($slowTestReporter),
            new TestFinishedSubscriber($slowTestReporter),
            new RunnerFinishedSubscriber($slowTestReporter),
        );
    }
}
