<?php

declare(strict_types=1);

use Rector\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnTypeWillChangeRector::class)
        ->configure([
            'ArrayAccess' => ['offsetExists'],
        ]);
};
