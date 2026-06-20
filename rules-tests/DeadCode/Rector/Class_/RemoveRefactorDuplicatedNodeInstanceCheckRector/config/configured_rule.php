<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Class_\RemoveRefactorDuplicatedNodeInstanceCheckRector;

return RectorConfig::configure()
    ->withRules([RemoveRefactorDuplicatedNodeInstanceCheckRector::class]);
