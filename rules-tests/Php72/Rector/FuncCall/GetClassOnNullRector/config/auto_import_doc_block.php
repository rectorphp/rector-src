<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php72\Rector\FuncCall\GetClassOnNullRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $services = $containerConfigurator->services();
    $services->set(GetClassOnNullRector::class);
};
