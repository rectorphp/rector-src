<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Utils\Rector\MakeUseOfContainsStmtsRector;

return RectorConfig::configure()
    ->withRules([
        MakeUseOfContainsStmtsRector::class,
    ]);
