<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

return RectorConfig::configure()
    ->withRules([SpatieEnumMethodCallToEnumConstRector::class]);
