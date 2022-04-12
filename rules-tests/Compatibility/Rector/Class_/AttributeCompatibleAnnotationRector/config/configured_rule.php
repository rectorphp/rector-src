<?php

declare(strict_types=1);

use Rector\Compatibility\Rector\Class_\AttributeCompatibleAnnotationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AttributeCompatibleAnnotationRector::class);
};
