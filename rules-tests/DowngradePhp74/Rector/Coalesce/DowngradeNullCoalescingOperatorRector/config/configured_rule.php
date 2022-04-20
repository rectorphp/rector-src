<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp74\Rector\Coalesce\DowngradeNullCoalescingOperatorRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DowngradeNullCoalescingOperatorRector::class);
};
