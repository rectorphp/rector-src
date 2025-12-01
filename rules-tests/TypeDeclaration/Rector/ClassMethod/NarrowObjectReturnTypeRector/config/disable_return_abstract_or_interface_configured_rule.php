<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\NarrowObjectReturnTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(NarrowObjectReturnTypeRector::class, [
        NarrowObjectReturnTypeRector::IS_ALLOW_ABSTRACT_AND_INTERFACE => false,
    ]);
};
