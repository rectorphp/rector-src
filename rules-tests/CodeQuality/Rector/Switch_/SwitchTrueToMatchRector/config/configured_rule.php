<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Switch_\SwitchTrueToMatchRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersion::PHP_80);
    $rectorConfig->rule(SwitchTrueToMatchRector::class);
};
