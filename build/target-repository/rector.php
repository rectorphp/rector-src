<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/rules',
        __DIR__ . '/packages',
        __DIR__ . '/config/set',
    ]);

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $parameters->set(Option::SKIP, [
        // test paths
        '*/Fixture/*',
        '*/Fixture*/*',
        '*/Source/*',
        '*/Source*/*',
        '*/Expected/*',
        '*/Expected*/*',
    ]);

    $parameters->set(Option::ENABLE_CACHE, true);
};
