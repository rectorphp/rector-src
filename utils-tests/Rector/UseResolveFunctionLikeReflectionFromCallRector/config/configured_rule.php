<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Utils\Rector\UseResolveFunctionLikeReflectionFromCallRector;

return RectorConfig::configure()
    ->withRules([
        UseResolveFunctionLikeReflectionFromCallRector::class,
    ]);
