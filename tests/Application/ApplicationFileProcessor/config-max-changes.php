<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->cacheDirectory(sys_get_temp_dir() . '/_rector_max_changes_test');
    $rectorConfig->rule(ClosureToArrowFunctionRector::class);
};
