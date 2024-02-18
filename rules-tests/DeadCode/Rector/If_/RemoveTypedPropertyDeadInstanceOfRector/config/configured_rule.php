<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveTypedPropertyDeadInstanceOfRector;

return RectorConfig::configure()
    ->withRules([RemoveTypedPropertyDeadInstanceOfRector::class]);
