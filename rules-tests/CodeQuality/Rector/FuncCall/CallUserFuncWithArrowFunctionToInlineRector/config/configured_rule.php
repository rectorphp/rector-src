<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\CallUserFuncWithArrowFunctionToInlineRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([CallUserFuncWithArrowFunctionToInlineRector::class]);
