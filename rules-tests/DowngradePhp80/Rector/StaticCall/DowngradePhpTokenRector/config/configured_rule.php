<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp80\Rector\StaticCall\DowngradePhpTokenRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DowngradePhpTokenRector::class);
};
