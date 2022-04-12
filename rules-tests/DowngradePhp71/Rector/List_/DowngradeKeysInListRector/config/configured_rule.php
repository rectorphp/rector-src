<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\DowngradePhp71\Rector\List_\DowngradeKeysInListRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(DowngradeKeysInListRector::class);
};
