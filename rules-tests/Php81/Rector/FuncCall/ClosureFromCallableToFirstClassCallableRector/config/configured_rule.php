<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\FuncCall\ClosureFromCallableToFirstClassCallableRector;

return RectorConfig::configure()
    ->withRules([ClosureFromCallableToFirstClassCallableRector::class]);
