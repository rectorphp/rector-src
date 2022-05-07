<?php

declare(strict_types=1);

use Rector\Php74\Rector\Property\TypedPropertyRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

/**
 * This e2e test is to be removed after fully migrated to RectorConfig
 */
return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(TypedPropertyRector::class);
};
