<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Class_\DeprecatedAnnotationToDeprecatedAttributeRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DeprecatedAnnotationToDeprecatedAttributeRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_84);
};
