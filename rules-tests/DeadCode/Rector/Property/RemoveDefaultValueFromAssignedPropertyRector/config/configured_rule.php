<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveDefaultValueFromAssignedPropertyRector;

return RectorConfig::configure()
    ->withRules([RemoveDefaultValueFromAssignedPropertyRector::class]);
