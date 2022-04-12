<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUnusedConstructorParamRector::class);
    $services->set(RemoveUnusedPrivatePropertyRector::class);
};
