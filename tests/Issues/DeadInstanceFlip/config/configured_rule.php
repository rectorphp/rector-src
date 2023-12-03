<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([RemoveDeadInstanceOfRector::class, FlipTypeControlToUseExclusiveTypeRector::class]);
};
