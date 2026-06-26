<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveMixedDocblockOverruledByNativeTypeRector;

return RectorConfig::configure()
    ->withRules([RemoveMixedDocblockOverruledByNativeTypeRector::class]);
