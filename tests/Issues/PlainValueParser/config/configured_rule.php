<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector as AnnotationToAttributeRectorAlias;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Tests\Issues\PlainValueParser\Source\CustomAnnotation;
use Rector\Tests\Issues\PlainValueParser\Source\CustomAnnotationWithName;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRectorAlias::class, [
        new AnnotationToAttribute(CustomAnnotation::class),
        new AnnotationToAttribute(CustomAnnotationWithName::class),
    ]);
};
