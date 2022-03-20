<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUnusedPrivatePropertyRector::class)
        ->configure([
            RemoveUnusedPrivatePropertyRector::REMOVE_ASSIGN_SIDE_EFFECT => false,
        ]);
};
