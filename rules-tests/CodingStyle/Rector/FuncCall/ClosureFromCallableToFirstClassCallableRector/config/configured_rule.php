<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([ClosureFromCallableToFirstClassCallableRector::class]);
