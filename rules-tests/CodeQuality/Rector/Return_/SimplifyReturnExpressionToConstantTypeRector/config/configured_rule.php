<?php

declare(strict_types=1);
use Rector\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector;

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SimplifyReturnExpressionToConstantTypeRector::class);
};
