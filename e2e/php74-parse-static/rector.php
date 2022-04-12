<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp72\Rector\FuncCall\DowngradeJsonDecodeNullAssociativeArgRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();

    $rectorConfig->paths([
        __DIR__ . '/tests',
    ]);

    $rectorConfig->rule(DowngradeJsonDecodeNullAssociativeArgRector::class);
};
