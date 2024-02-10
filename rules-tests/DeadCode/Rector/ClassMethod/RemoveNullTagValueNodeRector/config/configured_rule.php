<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveNullTagValueNodeRector;

return RectorConfig::configure()
    ->withRules([RemoveNullTagValueNodeRector::class]);
