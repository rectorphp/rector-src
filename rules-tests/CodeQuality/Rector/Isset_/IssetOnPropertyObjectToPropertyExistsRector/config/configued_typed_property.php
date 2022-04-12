<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::TYPED_PROPERTIES);

    $services = $containerConfigurator->services();
    $services->set(IssetOnPropertyObjectToPropertyExistsRector::class);
};
