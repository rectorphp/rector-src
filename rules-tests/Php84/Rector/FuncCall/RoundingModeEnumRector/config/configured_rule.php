<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\FuncCall\RoundingModeEnumRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RoundingModeEnumRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_84);
};
