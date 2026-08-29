<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ArrayExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodeQuality\Rector\If_\CompleteMissingIfElseBracketRector;
use Rector\CodeQuality\Rector\If_\ExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\ObjectExplicitBoolCompareRector;
use Rector\CodeQuality\Rector\If_\ShortenElseIfRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        CompleteMissingIfElseBracketRector::class,
        ArrayExplicitBoolCompareRector::class,
        ObjectExplicitBoolCompareRector::class,
        ExplicitBoolCompareRector::class,
        CombineIfRector::class,
        ShortenElseIfRector::class,
        SimplifyIfElseToTernaryRector::class,
    ]);
};
