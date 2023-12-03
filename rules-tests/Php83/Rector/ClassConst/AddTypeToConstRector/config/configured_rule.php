<?php

declare(strict_types=1);
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddTypeToConstRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_83);
};
