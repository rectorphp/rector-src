<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector;

return RectorConfig::configure()
    ->withRules([RemoveUnusedConstructorParamRector::class]);
