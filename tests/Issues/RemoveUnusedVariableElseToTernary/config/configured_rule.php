<?php

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        RemoveUnusedVariableAssignRector::class,
        SimplifyIfElseToTernaryRector::class
    ]);
};
