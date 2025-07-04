<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddTypeToConstRector::class);
    $rectorConfig->treatClassesAsFinal();

    $rectorConfig->phpVersion(PhpVersion::PHP_83);
};
