<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Attribute\SortAttributeNamedArgsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SortAttributeNamedArgsRector::class]);
