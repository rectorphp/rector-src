<?php

declare(strict_types=1);

use Rector\Php80\Rector\Class_\DoctrineAnnotationClassToAttributeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DoctrineAnnotationClassToAttributeRector::class)
        ->configure([
            DoctrineAnnotationClassToAttributeRector::REMOVE_ANNOTATIONS => false,
        ]);
};
