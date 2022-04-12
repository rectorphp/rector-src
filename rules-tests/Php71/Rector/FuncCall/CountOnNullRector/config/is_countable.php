<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php71\Rector\FuncCall\CountOnNullRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::IS_COUNTABLE);

    $services = $containerConfigurator->services();
    $services->set(CountOnNullRector::class);
};
