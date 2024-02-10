<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php72\Rector\While_\WhileEachToForeachRector;

return RectorConfig::configure()
    ->withRules([WhileEachToForeachRector::class]);
