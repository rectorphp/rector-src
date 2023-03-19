<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp73\Rector\ConstFetch\DowngradePhp73JsonConstRector;
use Rector\DowngradePhp74\Rector\Coalesce\DowngradeNullCoalescingOperatorRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([DowngradeNullCoalescingOperatorRector::class, DowngradePhp73JsonConstRector::class]);
};
