<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\ConsistentImplodeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ConsistentImplodeRector::class]);
