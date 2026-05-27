<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector;
use Rector\CodeQuality\Rector\FuncCall\SortCallLikeNamedArgsRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\MethodCall\RemoveNullArgOnNullDefaultParamRector;

return RectorConfig::configure()
    ->withRules([
        AddNameToNullArgumentRector::class,
        SortCallLikeNamedArgsRector::class,
        RemoveNullArgOnNullDefaultParamRector::class,
    ]);
