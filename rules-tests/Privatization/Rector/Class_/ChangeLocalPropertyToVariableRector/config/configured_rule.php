<?php

declare(strict_types=1);

use Rector\Privatization\Rector\Class_\ChangeLocalPropertyToVariableRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ChangeLocalPropertyToVariableRector::class);
};
