<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector;

return RectorConfig::configure()
    ->withRules([RemoveArgumentFromDefaultParentCallRector::class]);
