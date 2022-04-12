<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\CompleteDynamicPropertiesRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CompleteDynamicPropertiesRector::class);
};
