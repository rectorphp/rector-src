<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $services = $containerConfigurator->services();
    $services->set(StringClassNameToClassConstantRector::class);
};
