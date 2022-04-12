<?php

declare(strict_types=1);

use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ClassPropertyAssignToConstructorPromotionRector::class);
    $services->set(AnnotationToAttributeRector::class);
};
