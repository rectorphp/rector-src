<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\RemoveSoleValueSprintfRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([RemoveSoleValueSprintfRector::class]);
