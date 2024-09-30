<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeFromIterableMethodCallRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->rules([AddClosureParamTypeFromIterableMethodCallRector::class]);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
