<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php74\Rector\Property\TypedPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(TypedPropertyRector::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::UNION_TYPES - 1);
};
