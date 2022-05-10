<?php

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(\Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector::class);
    $rectorConfig->rule(\Rector\DeadCode\Rector\FunctionLike\RemoveOverriddenValuesRector::class);
};