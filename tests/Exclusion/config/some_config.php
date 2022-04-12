<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;
use Rector\DeadCode\Rector\Plus\RemoveDeadZeroAndOneOperationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveEmptyClassMethodRector::class);
    $services->set(RemoveDeadZeroAndOneOperationRector::class);
};
