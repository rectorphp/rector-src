<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ChangeReadOnlyVariableWithDefaultValueToConstantRector::class);
    $services->set(RemoveUnusedVariableAssignRector::class);
};
