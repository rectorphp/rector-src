<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector;

return RectorConfig::configure()
    ->withRules([RemoveUnusedPrivateMethodParameterRector::class]);
