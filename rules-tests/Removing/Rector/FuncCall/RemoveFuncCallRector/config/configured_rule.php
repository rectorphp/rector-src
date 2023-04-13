<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RemoveFuncCallRector::class, ['var_dump']);
};
