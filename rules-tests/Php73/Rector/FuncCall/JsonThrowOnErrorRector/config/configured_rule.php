<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;

return RectorConfig::configure()
    ->withRules([JsonThrowOnErrorRector::class]);
