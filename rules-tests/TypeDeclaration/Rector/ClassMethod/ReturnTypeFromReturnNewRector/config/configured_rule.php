<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnTypeFromReturnNewRector::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(
        \Rector\Core\Configuration\Option::PHP_VERSION_FEATURES,
        \Rector\Core\ValueObject\PhpVersionFeature::STATIC_RETURN_TYPE
    );
};
