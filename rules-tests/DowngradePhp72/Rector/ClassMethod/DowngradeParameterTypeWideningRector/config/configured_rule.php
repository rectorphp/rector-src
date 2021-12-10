<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Rector\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(DowngradeParameterTypeWideningRector::class)
        ->configure([
            DowngradeParameterTypeWideningRector::UNSAFE_TYPES_TO_METHODS => [
                ContainerInterface::class => ['set', 'get', 'has', 'initialized'],
                \Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture\SomeContainerInterface::class => [
                    'set',
                    'has',
                ],
            ],
        ]);
};
