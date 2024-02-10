<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php73\Rector\BooleanOr\IsCountableRector;

return RectorConfig::configure()
    ->withRules([IsCountableRector::class]);
