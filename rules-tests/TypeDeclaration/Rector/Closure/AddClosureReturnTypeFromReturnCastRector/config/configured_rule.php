<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Closure\AddClosureReturnTypeFromReturnCastRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddClosureReturnTypeFromReturnCastRector::class);
    $rectorConfig->phpVersion(PhpVersionFeature::SCALAR_TYPES);
};
