<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveMixedDocblockRector;

return RectorConfig::configure()
    ->withRules([RemoveMixedDocblockRector::class]);
