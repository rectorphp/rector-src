<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUselessTypeInParamTagRector;

return RectorConfig::configure()
    ->withRules([RemoveUselessTypeInParamTagRector::class]);
