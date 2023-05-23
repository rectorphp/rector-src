<?php

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        RemoveUnusedVariableAssignRector::class,
        AddDefaultValueForUndefinedVariableRector::class
    ]);
};
