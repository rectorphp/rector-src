<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector;

return RectorConfig::configure()
    ->withRules([RemoveDuplicatedCaseInSwitchRector::class]);
