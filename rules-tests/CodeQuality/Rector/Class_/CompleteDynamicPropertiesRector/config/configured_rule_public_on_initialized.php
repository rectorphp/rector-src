<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(CompleteDynamicPropertiesRector::class, [
        CompleteDynamicPropertiesRector::PRIVATE_ON_INITIALIZED => false,
    ]);
};
