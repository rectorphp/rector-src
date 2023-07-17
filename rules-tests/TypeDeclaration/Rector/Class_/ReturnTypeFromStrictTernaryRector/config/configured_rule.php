<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::NULLABLE_TYPE);
    $rectorConfig->rule(ReturnTypeFromStrictTernaryRector::class);
};
