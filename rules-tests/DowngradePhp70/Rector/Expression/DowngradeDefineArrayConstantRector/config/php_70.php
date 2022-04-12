<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DowngradePhp70\Rector\Expression\DowngradeDefineArrayConstantRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::SCALAR_TYPES - 1);

    $services = $containerConfigurator->services();
    $services->set(DowngradeDefineArrayConstantRector::class);
};
