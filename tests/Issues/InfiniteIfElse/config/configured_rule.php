<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\CodingStyle\Rector\Switch_\BinarySwitchToIfElseRector;
use Rector\EarlyReturn\Rector\If_\RemoveAlwaysElseRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        BinarySwitchToIfElseRector::class,
        RemoveAlwaysElseRector::class,
    ]);
};
