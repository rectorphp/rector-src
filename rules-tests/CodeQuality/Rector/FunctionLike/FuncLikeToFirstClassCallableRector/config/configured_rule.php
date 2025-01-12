<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FunctionLike\FuncLikeToFirstClassCallableRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([FuncLikeToFirstClassCallableRector::class]);
