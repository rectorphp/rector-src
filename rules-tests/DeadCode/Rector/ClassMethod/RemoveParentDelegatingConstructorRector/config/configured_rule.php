<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector;

return RectorConfig::configure()
    ->withRules([RemoveParentDelegatingConstructorRector::class]);
