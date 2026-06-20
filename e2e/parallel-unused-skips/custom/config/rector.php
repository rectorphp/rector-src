<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();

    $rectorConfig->paths([__DIR__ . '/../../src/']);

    $rectorConfig->rule(SimplifyUselessVariableRector::class);

    $rectorConfig->reportUnusedSkips();

    // matched in the worker - must be excluded from the unused report (proves parallel aggregation)
    // plus a glob that never matches - must be reported as unused
    $rectorConfig->skip([
        SimplifyUselessVariableRector::class => ['*/src/*'],
        '*/NonexistentUnused/*',
    ]);
};
