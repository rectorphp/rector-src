<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\ReturnIteratorInDataProviderRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReturnIteratorInDataProviderRector::class);
};
