<?php

declare(strict_types=1);

use Rector\DowngradePhp74\Rector\Coalesce\DowngradeNullCoalescingOperatorRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeNullCoalescingOperatorRector::class);
};
