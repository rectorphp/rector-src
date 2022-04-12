<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\DowngradePhp54\Rector\LNumber\DowngradeBinaryNotationRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(DowngradeBinaryNotationRector::class);
};
