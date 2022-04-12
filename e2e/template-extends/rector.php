<?php

declare(strict_types=1);
use Rector\Config\RectorConfig;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Core\Configuration\Option;

return static function (RectorConfig $rectorConfig): void {
    $parameters = $rectorConfig->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__.'/src/',
    ]);
};

