<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp72\Rector\ConstFetch\DowngradePhp72JsonConstRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DowngradePhp72JsonConstRector::class);
};
