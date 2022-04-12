<?php

declare(strict_types=1);

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\Compiler\\', __DIR__ . '/../src');
};
