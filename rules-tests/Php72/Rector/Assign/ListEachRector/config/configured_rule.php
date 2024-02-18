<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php72\Rector\Assign\ListEachRector;

return RectorConfig::configure()
    ->withRules([ListEachRector::class]);
