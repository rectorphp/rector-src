<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\CompactToVariablesRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([CompactToVariablesRector::class]);
