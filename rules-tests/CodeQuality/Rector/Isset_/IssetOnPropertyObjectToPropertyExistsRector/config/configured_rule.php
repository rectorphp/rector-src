<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(IssetOnPropertyObjectToPropertyExistsRector::class);
};
