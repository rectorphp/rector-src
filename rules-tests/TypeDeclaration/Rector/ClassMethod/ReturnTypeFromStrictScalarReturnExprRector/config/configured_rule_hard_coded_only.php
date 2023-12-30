<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictScalarReturnExprRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ReturnTypeFromStrictScalarReturnExprRector::class, [
        ReturnTypeFromStrictScalarReturnExprRector::HARD_CODED_ONLY => true,
    ]);
};
