<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Unambiguous\Rector\Class_\RemoveReturnThisFromSetterClassMethodRector;

return RectorConfig::configure()
    ->withRules([RemoveReturnThisFromSetterClassMethodRector::class]);
