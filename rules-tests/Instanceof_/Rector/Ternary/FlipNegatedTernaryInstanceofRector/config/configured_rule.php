<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Instanceof_\Rector\Ternary\FlipNegatedTernaryInstanceofRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(FlipNegatedTernaryInstanceofRector::class);
};
