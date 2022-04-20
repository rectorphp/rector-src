<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp72\Rector\FuncCall\DowngradeStreamIsattyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DowngradeStreamIsattyRector::class);
};
