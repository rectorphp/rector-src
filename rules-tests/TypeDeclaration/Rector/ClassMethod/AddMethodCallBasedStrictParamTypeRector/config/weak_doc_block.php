<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::UNION_TYPES - 1);

    $services = $containerConfigurator->services();
    $services->set(AddMethodCallBasedStrictParamTypeRector::class)
        ->call('configure', [[
            AddMethodCallBasedStrictParamTypeRector::TRUST_DOC_BLOCKS => true,
        ]]);
};
