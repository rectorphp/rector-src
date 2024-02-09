<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([StaticClosureRector::class]);
