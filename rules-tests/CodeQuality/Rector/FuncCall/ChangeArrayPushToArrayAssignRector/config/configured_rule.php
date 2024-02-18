<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\ChangeArrayPushToArrayAssignRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ChangeArrayPushToArrayAssignRector::class]);
