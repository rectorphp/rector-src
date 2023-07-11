<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        RemoveUnusedVariableInCatchRector::class,
        OptionalParametersAfterRequiredRector::class
    ]);
};
