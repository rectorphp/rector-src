<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->public();

    $services->load(
        'Rector\Core\Tests\DependencyInjection\CompilerPass\Source\\',
        __DIR__ . '/../CompilerPass/Source'
    )
        ->exclude([__DIR__ . '/../CompilerPass/Source/SkipMe']);
};
