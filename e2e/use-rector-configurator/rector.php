<?php

declare(strict_types=1);

use Rector\Core\Config\RectorConfigurator;
use Rector\Core\Configuration\Option;
use Rector\DowngradePhp81\Rector\Property\DowngradeReadonlyPropertyRector;

return static function (RectorConfigurator $rectorConfigurator): void {
    $parameters = $rectorConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
    ]);

    $services = $rectorConfigurator->services();
    $services->set(DowngradeReadonlyPropertyRector::class);
};

