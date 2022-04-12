<?php

declare(strict_types=1);

use Rector\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CreateFunctionToAnonymousFunctionRector::class);
};
