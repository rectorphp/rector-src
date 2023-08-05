<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\TypeDeclaration\Rector\Param\ParamTypeFromStrictTypedPropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::DEPRECATE_DYNAMIC_PROPERTIES);

    $rectorConfig->rule(ParamTypeFromStrictTypedPropertyRector::class);
};
