<?php

declare(strict_types=1);

use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;
use Rector\CodeQuality\Rector\Expression\TernaryFalseExpressionToIfRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ChangeSwitchToMatchRector::class);
    $rectorConfig->rule(TernaryFalseExpressionToIfRector::class);
};
