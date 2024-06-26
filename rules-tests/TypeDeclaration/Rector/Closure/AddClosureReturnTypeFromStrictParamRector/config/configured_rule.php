<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Closure\AddClosureReturnTypeFromStrictParamRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddClosureReturnTypeFromStrictParamRector::class);
    $rectorConfig->phpVersion(PhpVersion::PHP_74);
};
