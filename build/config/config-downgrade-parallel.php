<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $containerConfigurator->import(__DIR__ . '/config-downgrade.php');
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PARALLEL, true);
};
