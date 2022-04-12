<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $services = $containerConfigurator->services();
    $services->set(RenameFunctionRector::class)
        ->configure([
            'service' => 'Symfony\Component\DependencyInjection\Loader\Configurator\service',
        ]);
};
