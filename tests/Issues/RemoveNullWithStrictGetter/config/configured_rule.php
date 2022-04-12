<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\DeadCode\Rector\PropertyProperty\RemoveNullPropertyInitializationRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(TypedPropertyRector::class);

    $services->set(TypedPropertyFromStrictGetterMethodReturnTypeRector::class);

    // should be ignored if typed property is used
    $services->set(RemoveNullPropertyInitializationRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::UNION_TYPES - 1);
};
