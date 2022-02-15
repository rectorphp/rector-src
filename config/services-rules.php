<?php

declare(strict_types=1);

use Rector\PSR4\Composer\PSR4NamespaceMatcher;
use Rector\PSR4\Contract\PSR4AutoloadNamespaceMatcherInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    // psr-4
    $services->alias(PSR4AutoloadNamespaceMatcherInterface::class, PSR4NamespaceMatcher::class);

    $services->load('Rector\\', __DIR__ . '/../rules')
        ->exclude([
            __DIR__ . '/../rules/*/ValueObject/*',
            __DIR__ . '/../rules/*/Rector/*',
            __DIR__ . '/../rules/*/Contract/*',
            __DIR__ . '/../rules/*/Exception/*',
            __DIR__ . '/../rules/*/Enum/*',
        ]);
};
