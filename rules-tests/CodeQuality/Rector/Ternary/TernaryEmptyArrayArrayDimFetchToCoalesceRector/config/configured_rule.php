<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Ternary\TernaryEmptyArrayArrayDimFetchToCoalesceRector;

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TernaryEmptyArrayArrayDimFetchToCoalesceRector::class);
};
