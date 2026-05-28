<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\MethodCall\RemoveNullNamedArgOnNullDefaultParamRector;
use Rector\CodeQuality\Rector\CallLike\AddNameToNullArgumentRector;
use Rector\CodeQuality\Rector\FuncCall\SortCallLikeNamedArgsRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([
        AddNameToNullArgumentRector::class,
        SortCallLikeNamedArgsRector::class,
        RemoveNullNamedArgOnNullDefaultParamRector::class,
    ]);
