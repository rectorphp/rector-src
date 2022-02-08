<?php

declare(strict_types=1);

use Rector\DowngradePhp73\Rector\ConstFetch\DowngradePhp73JsonConstRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradePhp73JsonConstRector::class);
};
