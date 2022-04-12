<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Doctrine\Rector\Property\TypedPropertyFromToOneRelationTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::TYPED_PROPERTIES - 1);

    $services = $containerConfigurator->services();
    $services->set(TypedPropertyFromToOneRelationTypeRector::class);
};
