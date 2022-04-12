<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveEmptyClassMethodRector::class);
};
