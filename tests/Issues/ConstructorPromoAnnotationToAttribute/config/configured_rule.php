<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ClassPropertyAssignToConstructorPromotionRector::class);
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new \Rector\Php80\ValueObject\AnnotationToAttribute('OldTag', 'NewAttribute'),
    ]);
};
