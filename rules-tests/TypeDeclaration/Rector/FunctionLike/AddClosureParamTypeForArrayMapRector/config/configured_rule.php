<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayMapRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->rules([AddClosureParamTypeForArrayMapRector::class]);

    $rectorConfig->phpVersion(PhpVersionFeature::UNION_TYPES);
};
