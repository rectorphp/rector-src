<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;

return RectorConfig::configure()
    ->withRules([ChangeIfElseValueAssignToEarlyReturnRector::class, SimplifyIfElseToTernaryRector::class]);
