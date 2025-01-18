<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\AddNamedArgumentsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([AddNamedArgumentsRector::class]);
