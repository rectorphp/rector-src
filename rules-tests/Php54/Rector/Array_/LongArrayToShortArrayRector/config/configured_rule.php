<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php54\Rector\Array_\LongArrayToShortArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(LongArrayToShortArrayRector::class);
};
