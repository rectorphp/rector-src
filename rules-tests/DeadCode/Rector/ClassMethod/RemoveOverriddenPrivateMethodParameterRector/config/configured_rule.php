<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector;

return RectorConfig::configure()
    ->withRules([RemoveOverriddenPrivateMethodParameterRector::class]);
