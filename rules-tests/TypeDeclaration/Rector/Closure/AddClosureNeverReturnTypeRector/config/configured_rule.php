<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Closure\AddClosureNeverReturnTypeRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddClosureNeverReturnTypeRector::class);
    $rectorConfig->phpVersion(PhpVersionFeature::NEVER_TYPE);
};
