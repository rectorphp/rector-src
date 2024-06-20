<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AddTypeToConstRector::class, [
        AddTypeToConstRector::ALLOW_HAS_CHILD => true,
    ]);

    $rectorConfig->phpVersion(PhpVersion::PHP_83);
};
