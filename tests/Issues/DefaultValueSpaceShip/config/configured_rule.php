<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;
use Rector\Php70\Rector\If_\IfToSpaceshipRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddDefaultValueForUndefinedVariableRector::class);
    $rectorConfig->rule(IfToSpaceshipRector::class);
};
