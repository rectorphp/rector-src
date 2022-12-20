<?php

use Rector\Config\RectorConfig;
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        InlineConstructorDefaultToPropertyRector::class,
        TypedPropertyFromStrictConstructorRector::class,
    ]);
};