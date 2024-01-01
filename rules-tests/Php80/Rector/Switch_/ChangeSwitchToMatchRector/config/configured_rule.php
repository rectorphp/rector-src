<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersion::PHP_80);
    $rectorConfig->rule(ChangeSwitchToMatchRector::class);
};
