<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\BoolvalToTypeCastRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([BoolvalToTypeCastRector::class]);
