<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictGetterMethodReturnTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::STRINGABLE);

    $rectorConfig->rules([
        StringableForToStringRector::class,
        TypedPropertyFromStrictGetterMethodReturnTypeRector::class,
    ]);
};
