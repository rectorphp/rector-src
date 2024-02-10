<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanAnd\RemoveUselessIsObjectCheckRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([RemoveUselessIsObjectCheckRector::class]);
