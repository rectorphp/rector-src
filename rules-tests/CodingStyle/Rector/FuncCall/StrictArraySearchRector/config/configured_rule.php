<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\StrictArraySearchRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([StrictArraySearchRector::class]);
