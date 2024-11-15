<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;

return RectorConfig::configure()
    ->withRules([RemoveDoubleAssignRector::class, RemoveUnusedPrivateMethodRector::class]);
