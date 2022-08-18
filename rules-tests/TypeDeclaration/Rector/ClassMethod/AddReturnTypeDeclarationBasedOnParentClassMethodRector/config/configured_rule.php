<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationBasedOnParentClassMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddReturnTypeDeclarationBasedOnParentClassMethodRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::MIXED_TYPE);
};
