<?php

declare(strict_types=1);

use Rector\Carbon\Rector\New_\DateTimeInstanceToCarbonRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DateTimeInstanceToCarbonRector::class);
};
