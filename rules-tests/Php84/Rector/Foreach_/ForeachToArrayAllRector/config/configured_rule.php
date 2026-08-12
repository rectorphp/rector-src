<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Foreach_\ForeachToArrayAllRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ForeachToArrayAllRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_84);
};
