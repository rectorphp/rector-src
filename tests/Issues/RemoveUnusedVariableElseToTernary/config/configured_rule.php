<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([RemoveUnusedVariableAssignRector::class, SimplifyIfElseToTernaryRector::class]);
};
