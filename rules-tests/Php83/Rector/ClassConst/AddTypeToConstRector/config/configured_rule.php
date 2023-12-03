<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(\Rector\Php83\Rector\ClassConst\AddTypeToConstRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_83);
};
