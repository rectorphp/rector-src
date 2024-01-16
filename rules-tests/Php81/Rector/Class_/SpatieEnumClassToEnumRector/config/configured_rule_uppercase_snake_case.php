<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Class_\SpatieEnumClassToEnumRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(SpatieEnumClassToEnumRector::class, [
        SpatieEnumClassToEnumRector::TO_UPPER_SNAKE_CASE => true,
    ]);
};
