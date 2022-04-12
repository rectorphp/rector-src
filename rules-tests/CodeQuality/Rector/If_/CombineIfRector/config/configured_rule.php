<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\CombineIfRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CombineIfRector::class);
};
