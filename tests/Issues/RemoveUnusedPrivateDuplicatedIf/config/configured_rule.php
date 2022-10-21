<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodRector;
use Rector\DeadCode\Rector\FunctionLike\RemoveDuplicatedIfReturnRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        RemoveUnusedPrivateMethodRector::class,
        RemoveDuplicatedIfReturnRector::class,
    ]);
};
