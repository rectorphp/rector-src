<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Instanceof_\Rector\Ternary\FlipNegatedTernaryInstanceofRector;

return RectorConfig::configure()
    ->withRules([FlipNegatedTernaryInstanceofRector::class]);
