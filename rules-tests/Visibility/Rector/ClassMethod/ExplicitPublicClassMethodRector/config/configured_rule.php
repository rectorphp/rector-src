<?php

declare(strict_types=1);

use Rector\Visibility\Rector\ClassMethod\ExplicitPublicClassMethodRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ExplicitPublicClassMethodRector::class);
};
