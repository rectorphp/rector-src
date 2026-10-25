<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\FunctionLike\AddClosureParamTypeForArrayReduceRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->rules([AddClosureParamTypeForArrayReduceRector::class]);

    $rectorConfig->phpVersion(PhpVersion::PHP_80);
};
