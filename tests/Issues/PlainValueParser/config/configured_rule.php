<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\PlainValueParser\Source\CustomAnnotation;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector as AnnotationToAttributeRectorAlias;
use Rector\Php80\ValueObject\AnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRectorAlias::class, [
        new AnnotationToAttribute(CustomAnnotation::class),
    ]);
};
