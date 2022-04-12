<?php

declare(strict_types=1);

use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AddDefaultValueForUndefinedVariableRector::class);
    $services->set(RenameParamToMatchTypeRector::class);
};
