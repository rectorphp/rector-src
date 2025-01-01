<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Block\ReplaceBlockToItsStmtsRector;

return RectorConfig::configure()
    ->withRules([ReplaceBlockToItsStmtsRector::class]);
