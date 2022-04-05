<?php

declare(strict_types=1);

use Rector\Removing\Rector\Namespace_\RemoveNamespaceRector;
use Rector\Removing\ValueObject\RemoveNamespace;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveNamespaceRector::class)
        ->configure([new RemoveNamespace('App')]);
};
