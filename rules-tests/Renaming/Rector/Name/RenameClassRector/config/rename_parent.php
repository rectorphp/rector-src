<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\Renaming\Rector\Name\RenameClassRector::class)
        ->call('configure', [[
            \Rector\Renaming\Rector\Name\RenameClassRector::OLD_TO_NEW_CLASSES => [
                'ThisClassDoesNotExistAnymore' => 'NewClassThatDoesNotExistEither',
                'App\NotHereClass\AndNamespace' => 'NewClassThatDoesNotExistEither',
            ],
        ]]);
};
