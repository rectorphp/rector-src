<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\FuncCall\NullToStrictIntPregSlitFuncCallLimitArgRector;

return RectorConfig::configure()
    ->withRules([NullToStrictIntPregSlitFuncCallLimitArgRector::class]);
