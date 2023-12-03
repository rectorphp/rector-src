<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddTypeToConstRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_83);
};
