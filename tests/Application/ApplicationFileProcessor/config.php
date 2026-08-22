<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    // isolated cache dir, so parallel test processes cannot wipe the shared default cache
    // mid-run and flip the caching assertions of this test
    $rectorConfig->cacheDirectory(sys_get_temp_dir() . '/_rector_application_file_processor_test');
};
