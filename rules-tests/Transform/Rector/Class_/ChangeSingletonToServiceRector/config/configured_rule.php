<?php

declare(strict_types=1);

use Rector\Transform\Rector\Class_\ChangeSingletonToServiceRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ChangeSingletonToServiceRector::class);
};
