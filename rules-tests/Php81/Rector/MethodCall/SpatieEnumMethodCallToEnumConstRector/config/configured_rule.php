<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\MethodCall\SpatieEnumMethodCallToEnumConstRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(SpatieEnumMethodCallToEnumConstRector::class);
};
