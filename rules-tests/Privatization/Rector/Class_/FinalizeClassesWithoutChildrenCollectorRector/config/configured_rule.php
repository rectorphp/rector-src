<?php

declare(strict_types=1);

use Rector\Collector\ParentClassCollector;
use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenCollectorRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FinalizeClassesWithoutChildrenCollectorRector::class);
    $rectorConfig->collector(ParentClassCollector::class);

    // explicit opt-in required
    $rectorConfig->enableCollectors();
};
