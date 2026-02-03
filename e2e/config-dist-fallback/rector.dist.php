<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    // This rule should be applied when rector.dist.php is used as fallback
    $rectorConfig->rule(RemoveUselessVarTagRector::class);
};
