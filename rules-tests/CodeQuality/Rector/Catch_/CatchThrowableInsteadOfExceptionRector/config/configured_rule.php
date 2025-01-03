<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Catch_\CatchThrowableInsteadOfExceptionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([CatchThrowableInsteadOfExceptionRector::class]);
