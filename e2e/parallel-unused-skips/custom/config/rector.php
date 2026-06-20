<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->parallel();

    $rectorConfig->paths([__DIR__ . '/../../src/']);

    $rectorConfig->rule(SimplifyUselessVariableRector::class);

    $rectorConfig->reportUnusedSkips();

    // two concrete paths under one rule: first matched in the worker (proves parallel aggregation),
    // second never matches and must be reported per-path as unused
    $rectorConfig->skip([
        SimplifyUselessVariableRector::class => [
            dirname(__DIR__, 2) . '/src/SomeClass.php',
            dirname(__DIR__, 2) . '/src/NonexistentUnused.php',
        ],
    ]);
};
