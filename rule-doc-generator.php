<?php

declare(strict_types=1);

use Rector\RuleDocGenerator\Category\RectorCategoryInferer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire();

    $services->set(RectorCategoryInferer::class);
};
