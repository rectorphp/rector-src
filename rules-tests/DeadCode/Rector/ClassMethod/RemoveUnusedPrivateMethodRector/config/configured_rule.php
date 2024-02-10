<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;

return RectorConfig::configure()
    ->withRules([RemoveUnusedPrivateMethodRector::class]);
