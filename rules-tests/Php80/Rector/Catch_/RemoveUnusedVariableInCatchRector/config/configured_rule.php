<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_80);

    $services = $containerConfigurator->services();
    $services->set(RemoveUnusedVariableInCatchRector::class);
};
