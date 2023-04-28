<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;
use Rector\Php70\Rector\If_\IfToSpaceshipRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules(
        [
            AddDefaultValueForUndefinedVariableRector::class,
            IfToSpaceshipRector::class,
            ClosureToArrowFunctionRector::class,
        ]
    );
};
