<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules(
        [
            ArrayFirstLastRector::class,
        ]
    );
};
