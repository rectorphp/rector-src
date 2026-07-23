<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector;

return RectorConfig::configure()
    ->withRules([RemoveParentDelegatingClassMethodRector::class]);
