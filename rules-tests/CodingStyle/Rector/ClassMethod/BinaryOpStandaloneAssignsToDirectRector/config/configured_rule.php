<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([BinaryOpStandaloneAssignsToDirectRector::class]);
