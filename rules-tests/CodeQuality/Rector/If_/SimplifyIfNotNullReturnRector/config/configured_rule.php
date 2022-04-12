<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SimplifyIfNotNullReturnRector::class);
};
