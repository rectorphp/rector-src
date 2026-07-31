<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php73\Rector\FuncCall\ArrayKeysToArrayKeyFirstLastRector;

return RectorConfig::configure()
    ->withRules([ArrayKeysToArrayKeyFirstLastRector::class]);
