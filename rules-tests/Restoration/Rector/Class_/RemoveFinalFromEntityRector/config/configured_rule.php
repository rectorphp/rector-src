<?php

declare(strict_types=1);

use Rector\Restoration\Rector\Class_\RemoveFinalFromEntityRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveFinalFromEntityRector::class);
};
