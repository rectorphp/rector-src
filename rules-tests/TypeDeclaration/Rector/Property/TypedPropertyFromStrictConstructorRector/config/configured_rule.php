<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TypedPropertyFromStrictConstructorRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::INTERSECTION_TYPES);
};
