<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // This rule should NOT be applied because rector.php takes priority
    $rectorConfig->rule(ClosureToArrowFunctionRector::class);
};
