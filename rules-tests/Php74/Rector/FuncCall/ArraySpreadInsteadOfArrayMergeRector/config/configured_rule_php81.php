<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_81);

    $services = $containerConfigurator->services();
    $services->set(ArraySpreadInsteadOfArrayMergeRector::class);
};
