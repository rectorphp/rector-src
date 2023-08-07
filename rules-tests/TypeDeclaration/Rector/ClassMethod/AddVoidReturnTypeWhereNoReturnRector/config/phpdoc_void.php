<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(\Rector\Core\ValueObject\PhpVersionFeature::VOID_TYPE);

    $rectorConfig
        ->ruleWithConfiguration(AddVoidReturnTypeWhereNoReturnRector::class, [
            AddVoidReturnTypeWhereNoReturnRector::USE_PHPDOC => true,
        ]);
};
