<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Foreach_\ForeachToArrayFindRector;

return RectorConfig::configure()
    ->withRules([ForeachToArrayFindRector::class]);
