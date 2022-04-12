<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Ternary\TernaryConditionVariableAssignmentRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(TernaryConditionVariableAssignmentRector::class);
};
