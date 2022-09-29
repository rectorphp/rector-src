<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector;
use Rector\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveDeadZeroAndOneOperationRector::class);
    $rectorConfig->rule(ChangeReadOnlyPropertyWithDefaultValueToConstantRector::class);
};
