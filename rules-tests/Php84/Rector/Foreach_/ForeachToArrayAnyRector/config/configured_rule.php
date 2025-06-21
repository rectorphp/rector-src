<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Foreach_\ForeachToArrayAnyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ForeachToArrayAnyRector::class);
};
