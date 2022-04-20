<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\List_\ListToArrayDestructRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ListToArrayDestructRector::class);
};
