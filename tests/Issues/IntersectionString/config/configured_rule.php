<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php81\Rector\FunctionLike\IntersectionTypesRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::INTERSECTION_TYPES);

    $services = $containerConfigurator->services();
    $services->set(IntersectionTypesRector::class);
    $services->set(AddMethodCallBasedStrictParamTypeRector::class);
};
