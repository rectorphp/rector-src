<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\Class_\ChangeSingletonToServiceRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ChangeSingletonToServiceRector::class);
};
