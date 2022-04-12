<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenamePropertyToMatchTypeRector::class);
    $services->set(CompleteDynamicPropertiesRector::class);
};
