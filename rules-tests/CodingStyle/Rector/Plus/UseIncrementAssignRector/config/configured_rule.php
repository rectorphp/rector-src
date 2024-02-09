<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Plus\UseIncrementAssignRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([UseIncrementAssignRector::class]);
