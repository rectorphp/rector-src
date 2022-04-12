<?php

declare(strict_types=1);

use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(AnnotationToAttributeRector::class)
        ->configure([]);
};
