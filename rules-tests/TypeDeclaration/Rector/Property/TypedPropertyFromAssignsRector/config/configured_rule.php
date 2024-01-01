<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TypedPropertyFromAssignsRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::TYPED_PROPERTIES);
};
