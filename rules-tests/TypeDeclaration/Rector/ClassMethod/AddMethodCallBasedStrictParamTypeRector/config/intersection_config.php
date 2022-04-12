<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\Rector\ClassMethod\AddMethodCallBasedStrictParamTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::INTERSECTION_TYPES);

    $services = $containerConfigurator->services();
    $services->set(AddMethodCallBasedStrictParamTypeRector::class);
};
