<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\For_\ForToForeachRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ForToForeachRector::class);
};
