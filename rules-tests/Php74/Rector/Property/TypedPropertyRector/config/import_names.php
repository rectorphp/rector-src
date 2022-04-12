<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php74\Rector\Property\TypedPropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(TypedPropertyRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::UNION_TYPES);
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
};
