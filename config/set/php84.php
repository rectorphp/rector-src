<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php84\Rector\Param\ExplicitNullableParamTypeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        ExplicitNullableParamTypeRector::class,
    ]);
};
