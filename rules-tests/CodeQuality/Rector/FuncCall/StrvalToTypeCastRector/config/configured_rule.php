<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\StrvalToTypeCastRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(StrvalToTypeCastRector::class);
};
