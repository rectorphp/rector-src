<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Ternary\SwitchNegatedTernaryRector;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDeadInstanceOfRector::class);
    $services->set(SwitchNegatedTernaryRector::class);
};
