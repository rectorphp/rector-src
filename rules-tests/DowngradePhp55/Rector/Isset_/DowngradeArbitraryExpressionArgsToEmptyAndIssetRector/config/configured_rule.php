<?php

declare(strict_types=1);

use Rector\DowngradePhp55\Rector\Isset_\DowngradeArbitraryExpressionArgsToEmptyAndIssetRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeArbitraryExpressionArgsToEmptyAndIssetRector::class);
};
