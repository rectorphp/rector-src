<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\DowngradePhp70\Rector\Instanceof_\DowngradeInstanceofThrowableRector;

return static function (RectorConfig $rectorConfig): void {
    $services = $rectorConfig->services();
    $services->set(DowngradeInstanceofThrowableRector::class);
};
