<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReturnTypeFromStrictScalarReturnExprRector::class);
};
