<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\New_\FilesystemIteratorSkipDotsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FilesystemIteratorSkipDotsRector::class);
};
