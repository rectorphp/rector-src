<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanNot\ReplaceMultipleBooleanNotRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ReplaceMultipleBooleanNotRector::class]);
