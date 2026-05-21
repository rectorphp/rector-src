<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([AddNameToNullArgumentRector::class]);
