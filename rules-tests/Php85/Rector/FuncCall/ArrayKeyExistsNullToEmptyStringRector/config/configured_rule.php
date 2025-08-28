<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php85\Rector\FuncCall\ArrayKeyExistsNullToEmptyStringRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ArrayKeyExistsNullToEmptyStringRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_85);
};
