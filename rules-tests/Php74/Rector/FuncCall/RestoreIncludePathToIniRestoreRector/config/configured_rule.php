<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\FuncCall\RestoreIncludePathToIniRestoreRector;

return RectorConfig::configure()
    ->withRules([RestoreIncludePathToIniRestoreRector::class]);
