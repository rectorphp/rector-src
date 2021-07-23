<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\PHPUnit\Rector\Class_\AddProphecyTraitRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayParamDocTypeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $services = $containerConfigurator->services();
    $services->set(AddProphecyTraitRector::class);
    $services->set(AddArrayParamDocTypeRector::class);
};
