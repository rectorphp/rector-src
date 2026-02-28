<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\StrictInArrayRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([StrictInArrayRector::class]);
