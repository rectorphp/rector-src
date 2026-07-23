<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Expression\RemovePsr4AutoloadedIncludeRector;

return RectorConfig::configure()
    ->withConfiguredRule(RemovePsr4AutoloadedIncludeRector::class, [
        RemovePsr4AutoloadedIncludeRector::COMPOSER_JSON_PATH => __DIR__ . '/../Source/composer.json',
    ]);
