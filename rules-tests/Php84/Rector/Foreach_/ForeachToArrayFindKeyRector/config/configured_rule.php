<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Foreach_\ForeachToArrayFindKeyRector;

return RectorConfig::configure()
    ->withRules([ForeachToArrayFindKeyRector::class]);