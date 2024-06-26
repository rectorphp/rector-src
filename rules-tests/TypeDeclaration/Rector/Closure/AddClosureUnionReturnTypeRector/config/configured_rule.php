<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Closure\AddClosureUnionReturnTypeRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddClosureUnionReturnTypeRector::class);
    $rectorConfig->phpVersion(PhpVersionFeature::UNION_TYPES);
};
