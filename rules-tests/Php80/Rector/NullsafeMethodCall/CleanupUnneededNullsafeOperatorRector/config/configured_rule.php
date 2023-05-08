<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\NullsafeMethodCall\CleanupUnneededNullsafeOperatorRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(CleanupUnneededNullsafeOperatorRector::class);
};
