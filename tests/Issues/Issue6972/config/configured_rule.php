<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;
use Rector\Php70\Rector\FunctionLike\ExceptionHandlerTypehintRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ExceptionHandlerTypehintRector::class);
    $services->set(CatchExceptionNameMatchingTypeRector::class);
    $services->set(AddDefaultValueForUndefinedVariableRector::class);
};
