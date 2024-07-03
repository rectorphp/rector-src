<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNullableTypeRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReturnNullableTypeRector::class);
    $rectorConfig->phpVersion(PhpVersionFeature::NULLABLE_TYPE);
};
