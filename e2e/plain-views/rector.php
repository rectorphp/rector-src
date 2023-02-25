<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\NotEqual\CommonNotEqualRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/src/',
    ]);

    $rectorConfig->rule(CommonNotEqualRector::class);
    $rectorConfig->importNames();
    $rectorConfig->fileExtensions(['php', 'phtml']);
};
