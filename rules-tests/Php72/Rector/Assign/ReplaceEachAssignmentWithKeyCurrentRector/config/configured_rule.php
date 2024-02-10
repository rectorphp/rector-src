<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php72\Rector\Assign\ReplaceEachAssignmentWithKeyCurrentRector;

return RectorConfig::configure()
    ->withRules([ReplaceEachAssignmentWithKeyCurrentRector::class]);
