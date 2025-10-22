<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Utils\Rector\RemoveRefactorDuplicatedNodeInstanceCheckRector;

return RectorConfig::configure()
    ->withRules([
        RemoveRefactorDuplicatedNodeInstanceCheckRector::class,
    ]);
