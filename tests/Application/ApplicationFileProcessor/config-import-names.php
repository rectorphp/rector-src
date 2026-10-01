<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\StringableForToStringRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->cacheDirectory(sys_get_temp_dir() . '/_rector_import_names_test');
    $rectorConfig->importNames();
    $rectorConfig->rule(StringableForToStringRector::class);
};
