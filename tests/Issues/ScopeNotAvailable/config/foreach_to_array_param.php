<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\OptionalParametersAfterRequiredRector;
use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Foreach_\ForeachToArrayFindRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([ForeachToArrayFindRector::class, OptionalParametersAfterRequiredRector::class]);

    $rectorConfig->phpVersion(PhpVersion::PHP_84);
};
