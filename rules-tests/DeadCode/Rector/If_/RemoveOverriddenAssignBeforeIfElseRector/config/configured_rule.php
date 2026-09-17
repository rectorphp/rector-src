<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveOverriddenAssignBeforeIfElseRector;

return RectorConfig::configure()
    ->withRules([RemoveOverriddenAssignBeforeIfElseRector::class]);
