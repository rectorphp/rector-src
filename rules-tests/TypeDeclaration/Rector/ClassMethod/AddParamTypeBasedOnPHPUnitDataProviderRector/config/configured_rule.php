<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeBasedOnPHPUnitDataProviderRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddParamTypeBasedOnPHPUnitDataProviderRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::UNION_TYPES - 1);
};
