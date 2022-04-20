<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DowngradePhp70\Rector\Expr\DowngradeUnnecessarilyParenthesizedExpressionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DowngradeUnnecessarilyParenthesizedExpressionRector::class);
};
