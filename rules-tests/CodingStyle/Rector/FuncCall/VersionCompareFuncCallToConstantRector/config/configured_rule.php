<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\VersionCompareFuncCallToConstantRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([VersionCompareFuncCallToConstantRector::class]);
