<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SimplifyForeachToArrayFilterRector::class);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::ARROW_FUNCTION);
};
