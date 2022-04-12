<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src']);
};
