<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Concat\JoinStringConcatRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([JoinStringConcatRector::class]);
