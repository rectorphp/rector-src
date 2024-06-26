<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FunctionLike\SimplifyUselessVariableRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(SimplifyUselessVariableRector::class, [
            SimplifyUselessVariableRector::ONLY_DIRECT_ASSIGN => true,
        ]);
};
