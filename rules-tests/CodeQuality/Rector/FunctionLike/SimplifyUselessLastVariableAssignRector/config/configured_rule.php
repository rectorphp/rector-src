<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessLastVariableAssignRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SimplifyUselessLastVariableAssignRector::class);
};
