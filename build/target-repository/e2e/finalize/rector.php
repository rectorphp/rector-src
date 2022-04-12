<?php

declare(strict_types=1);

use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(FinalizeClassesWithoutChildrenRector::class);
};
