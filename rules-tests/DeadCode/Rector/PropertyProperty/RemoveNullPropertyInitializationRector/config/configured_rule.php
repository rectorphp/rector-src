<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector;

return RectorConfig::configure()->withRules([RemoveNullPropertyInitializationRector::class]);
