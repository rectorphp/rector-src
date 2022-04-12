<?php

declare(strict_types=1);

use Rector\Php55\Rector\Class_\ClassConstantToSelfClassRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ClassConstantToSelfClassRector::class);
};
