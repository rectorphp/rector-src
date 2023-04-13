<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        EmptyOnNullableObjectToInstanceOfRector::class,
        \Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector::class,
        \Rector\CodeQuality\Rector\FuncCall\InlineIsAInstanceOfRector::class,
        \Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector::class,
        \Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector::class,
        \Rector\DeadCode\Rector\BinaryOp\RemoveDuplicatedInstanceOfRector::class,
        \Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector::class,
    ]);
};
