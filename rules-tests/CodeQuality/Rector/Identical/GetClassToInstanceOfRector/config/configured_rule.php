<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([GetClassToInstanceOfRector::class]);
