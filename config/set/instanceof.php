<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\InlineIsAInstanceOfRector;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\BinaryOp\RemoveDuplicatedInstanceOfRector;
use Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector;
use Rector\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        EmptyOnNullableObjectToInstanceOfRector::class,
        GetClassToInstanceOfRector::class,
        InlineIsAInstanceOfRector::class,
        FlipTypeControlToUseExclusiveTypeRector::class,
        RemoveDuplicatedInstanceOfRector::class,
        RemoveDeadInstanceOfRector::class,
    ]);
};
