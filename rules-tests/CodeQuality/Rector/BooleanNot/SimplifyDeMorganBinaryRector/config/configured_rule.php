<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SimplifyDeMorganBinaryRector::class]);
