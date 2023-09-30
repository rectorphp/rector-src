<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->disableParallel();

    $rectorConfig->paths([
        __DIR__ . '/src/', // correct path
        __DIR__ . '/does-not-exist/'
    ]);
};
