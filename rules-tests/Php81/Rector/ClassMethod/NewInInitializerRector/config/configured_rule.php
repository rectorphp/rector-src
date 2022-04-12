<?php

declare(strict_types=1);

use Rector\Php81\Rector\ClassMethod\NewInInitializerRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(NewInInitializerRector::class);
};
