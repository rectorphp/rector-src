<?php

declare(strict_types=1);

use Rector\Laravel\Rector\PropertyFetch\OptionalToNullsafeOperatorRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(OptionalToNullsafeOperatorRector::class);
};
