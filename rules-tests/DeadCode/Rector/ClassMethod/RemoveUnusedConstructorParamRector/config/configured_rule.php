<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUnusedConstructorParamRector::class);
};
