<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\MethodCall\RenameDeprecatedMethodCallRector;

return RectorConfig::configure()
    ->withRules([RenameDeprecatedMethodCallRector::class]);
