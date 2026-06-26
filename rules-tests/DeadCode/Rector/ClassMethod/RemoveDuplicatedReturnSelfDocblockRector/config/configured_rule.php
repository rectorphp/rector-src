<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveDuplicatedReturnSelfDocblockRector;

return RectorConfig::configure()
    ->withRules([RemoveDuplicatedReturnSelfDocblockRector::class]);
