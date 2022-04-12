<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(GetClassToInstanceOfRector::class);
};
