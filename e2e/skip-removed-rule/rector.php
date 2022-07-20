<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->skip([
        // UnwrapFutureCompatibleIfFunctionExistsRector is no longer exists
        // so it must throw Exception
        \Rector\DeadCode\Rector\If_\UnwrapFutureCompatibleIfFunctionExistsRector::class
    ]);
};
