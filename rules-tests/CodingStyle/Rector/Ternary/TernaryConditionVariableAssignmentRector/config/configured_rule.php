<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Ternary\TernaryConditionVariableAssignmentRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withRules([TernaryConditionVariableAssignmentRector::class]);
