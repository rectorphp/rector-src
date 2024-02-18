<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;

return RectorConfig::configure()
    ->withRules([ChangeIfElseValueAssignToEarlyReturnRector::class]);
