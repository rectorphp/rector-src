<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;
use Rector\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector;
use Rector\Config\RectorConfig;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        OptionalParametersAfterRequiredRector::class,
        ThrowWithPreviousExceptionRector::class,
        AddDefaultValueForUndefinedVariableRector::class,
    ]);
};
