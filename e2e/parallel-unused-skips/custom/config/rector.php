<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();

    $rectorConfig->paths([__DIR__ . '/../../src/']);

    $rectorConfig->rule(SimplifyUselessVariableRector::class);

    $rectorConfig->reportUnusedSkips();

    // two rule-scoped masks: first matched in the worker (proves parallel aggregation),
    // second never matches and must be reported per-path as unused.
    // a global mask "*/NonexistentGlobal/*" would NOT be reported, only rule-scoped ones are
    $rectorConfig->skip([
        SimplifyUselessVariableRector::class => [
            '*/src/*',
            '*/NonexistentUnused/*',
        ],
    ]);
};
