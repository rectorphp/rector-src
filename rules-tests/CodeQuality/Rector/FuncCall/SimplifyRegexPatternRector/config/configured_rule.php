<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([SimplifyRegexPatternRector::class]);
