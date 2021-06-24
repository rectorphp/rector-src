<?php

declare(strict_types=1);

use Rector\RemovingStatic\Rector\Class_\NewUniqueObjectToEntityFactoryRector;
use Rector\RemovingStatic\Rector\Class_\PassFactoryToUniqueObjectRector;
use Rector\Tests\RemovingStatic\Rector\Class_\PassFactoryToEntityRector\Fixture\AnotherClassWithMoreArguments;
use Rector\Tests\RemovingStatic\Rector\Class_\PassFactoryToEntityRector\Source\TurnMeToService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $typesToServices = [TurnMeToService::class, AnotherClassWithMoreArguments::class];

    $services->set(PassFactoryToUniqueObjectRector::class)
        ->call('configure', [[
            PassFactoryToUniqueObjectRector::TYPES_TO_SERVICES => $typesToServices,
        ]]);

    $services->set(NewUniqueObjectToEntityFactoryRector::class)
        ->call('configure', [[
            NewUniqueObjectToEntityFactoryRector::TYPES_TO_SERVICES => $typesToServices,
        ]]);
};
