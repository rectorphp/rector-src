<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Closure\AddClosureReturnTypeFromStrictNativeCallRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddClosureReturnTypeFromStrictNativeCallRector::class);
    $rectorConfig->phpVersion(PhpVersionFeature::SCALAR_TYPES);
};
