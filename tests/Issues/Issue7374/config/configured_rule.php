<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPrivateMethodParameterRector;
use Rector\Naming\Rector\ClassMethod\RenameVariableToMatchNewTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        RemoveUnusedPrivateMethodParameterRector::class,
        RenameVariableToMatchNewTypeRector::class,
    ]);
};
