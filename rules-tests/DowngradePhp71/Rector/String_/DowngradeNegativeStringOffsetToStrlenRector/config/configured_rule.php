<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp71\Rector\String_\DowngradeNegativeStringOffsetToStrlenRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DowngradeNegativeStringOffsetToStrlenRector::class);
};
