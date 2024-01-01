<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnNewRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReturnTypeFromReturnNewRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::STATIC_RETURN_TYPE);
};
