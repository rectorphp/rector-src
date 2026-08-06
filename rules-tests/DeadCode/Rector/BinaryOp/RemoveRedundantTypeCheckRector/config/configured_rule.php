<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\BinaryOp\RemoveRedundantTypeCheckRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveRedundantTypeCheckRector::class);
};
