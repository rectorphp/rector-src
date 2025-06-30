<?php

use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        \Rector\Php84\Rector\Foreach_\ForeachToArrayFindRector::class,
        \Rector\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector::class,
    ]);
    $rectorConfig->phpVersion(PhpVersion::PHP_84);
};
