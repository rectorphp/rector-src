<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\OrderAttributesRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(OrderAttributesRector::class, [OrderAttributesRector::ALPHABETICALLY]);
};
