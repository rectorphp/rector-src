<?php

declare(strict_types=1);

use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameVariableToMatchMethodCallReturnTypeRector::class);
};
