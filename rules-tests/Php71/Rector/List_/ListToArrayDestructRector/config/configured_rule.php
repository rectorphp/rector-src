<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\List_\ListToArrayDestructRector;

return RectorConfig::configure()
    ->withRules([ListToArrayDestructRector::class]);
