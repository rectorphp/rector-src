<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveTestsOverriddenPrivateMethodParameterRector;

return RectorConfig::configure()
    ->withRules([RemoveTestsOverriddenPrivateMethodParameterRector::class]);
