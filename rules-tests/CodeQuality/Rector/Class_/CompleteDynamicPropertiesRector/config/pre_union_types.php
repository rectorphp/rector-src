<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::UNION_TYPES - 1);

    $services = $containerConfigurator->services();
    $services->set(CompleteDynamicPropertiesRector::class);
};
