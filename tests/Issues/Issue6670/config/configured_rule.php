<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;

return RectorConfig::configure()->withRules([RemoveUnusedPrivateMethodRector::class, RemoveAlwaysElseRector::class]);
