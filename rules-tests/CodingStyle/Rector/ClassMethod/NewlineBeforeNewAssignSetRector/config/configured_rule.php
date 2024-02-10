<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\NewlineBeforeNewAssignSetRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([NewlineBeforeNewAssignSetRector::class]);
