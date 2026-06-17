<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\StmtsAwareInterface\MoveInnerFunctionToTopLevelRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([MoveInnerFunctionToTopLevelRector::class]);
