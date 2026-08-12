<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Foreach_\ForeachToArrayAnyRector;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ForeachToArrayAnyRector::class);

    $rectorConfig->phpVersion(PhpVersion::PHP_84);
};
