<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(DynamicDocBlockPropertyToNativePropertyRector::class);
};
