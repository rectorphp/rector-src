<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\FuncCall\CallUserFuncToMethodCallRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([CallUserFuncToMethodCallRector::class]);
