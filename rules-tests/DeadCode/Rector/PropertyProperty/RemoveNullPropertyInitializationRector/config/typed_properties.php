<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::TYPED_PROPERTIES);

    $services = $containerConfigurator->services();
    $services->set(RemoveNullPropertyInitializationRector::class);
};
