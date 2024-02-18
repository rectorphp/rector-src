<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php53\Rector\Ternary\TernaryToElvisRector;

return RectorConfig::configure()
    ->withRules([TernaryToElvisRector::class]);
