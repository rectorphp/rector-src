<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveAlwaysTrueIfConditionRector::class);
};
