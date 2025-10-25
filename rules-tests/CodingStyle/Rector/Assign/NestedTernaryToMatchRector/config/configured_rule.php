<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Assign\NestedTernaryToMatchRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([NestedTernaryToMatchRector::class]);
