<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddTypeFromResourceDocblockRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AddTypeFromResourceDocblockRector::class, ['App\ValueObject\Resource']);
    $rectorConfig->phpVersion(PhpVersionFeature::NULLABLE_TYPE);
};
