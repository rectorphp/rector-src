<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector;

return RectorConfig::configure()
    ->withRules([RemoveReturnTagIncompatibleWithNativeTypeRector::class]);
