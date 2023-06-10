<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveTypedPropertyDeadInstanceOfRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveTypedPropertyDeadInstanceOfRector::class);
};
