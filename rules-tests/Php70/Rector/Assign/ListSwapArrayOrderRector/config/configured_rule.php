<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\Assign\ListSwapArrayOrderRector;

return RectorConfig::configure()
    ->withRules([ListSwapArrayOrderRector::class]);
