<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Testing\RectorRuleShouldNotBeApplied\Source\NoChangeRector;

return RectorConfig::configure()
    ->withRules([NoChangeRector::class]);
