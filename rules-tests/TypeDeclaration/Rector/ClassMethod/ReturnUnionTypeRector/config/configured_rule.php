<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnUnionTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReturnUnionTypeRector::class);
    $rectorConfig->phpVersion(PhpVersionFeature::UNION_TYPES);
};
