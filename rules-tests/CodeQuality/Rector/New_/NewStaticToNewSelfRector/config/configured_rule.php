<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\New_\NewStaticToNewSelfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([NewStaticToNewSelfRector::class]);
