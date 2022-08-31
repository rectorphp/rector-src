<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->skip([
        // windows slashes
        __DIR__ . '\non-existing-path',
        __DIR__ . '/../Fixture',
        '*\Mask\*',
    ]);
};
