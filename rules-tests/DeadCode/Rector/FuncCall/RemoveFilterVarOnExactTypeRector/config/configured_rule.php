<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\FuncCall\RemoveFilterVarOnExactTypeRector;

return RectorConfig::configure()
    ->withRules([RemoveFilterVarOnExactTypeRector::class]);
