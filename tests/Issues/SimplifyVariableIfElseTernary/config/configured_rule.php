<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules(
        [
            SimplifyIfElseToTernaryRector::class,
            SimplifyUselessVariableRector::class,
            CompleteDynamicPropertiesRector::class,
        ]
    );
