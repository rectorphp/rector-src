<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp81\Rector\Array_\DowngradeArraySpreadStringKeyRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(DowngradeArraySpreadStringKeyRector::class);
};
