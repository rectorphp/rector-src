<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveConcatAutocastRector::class);
};
