<?php

declare(strict_types=1);

use Rector\Carbon\Rector\FuncCall\TimeFuncCallToCarbonRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TimeFuncCallToCarbonRector::class);
};
