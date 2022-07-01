<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Lumen\Rector\MethodCall\RoutesStringMiddlewareToArrayRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RoutesStringMiddlewareToArrayRector::class);
};
