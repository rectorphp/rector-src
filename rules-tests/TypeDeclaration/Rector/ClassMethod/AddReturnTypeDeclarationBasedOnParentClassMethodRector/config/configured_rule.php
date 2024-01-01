<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddReturnTypeDeclarationBasedOnParentClassMethodRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
