<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\Rector\Property\NestedAnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationPropertyToAttributeClass;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Php80\ValueObject\NestedAnnotationToAttribute;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\Source\GenericAnnotation;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\All;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\Length;
use Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\NotNumber;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    // covers https://wiki.php.net/rfc/new_in_initializers#nested_attributes
    $rectorConfig->phpVersion(PhpVersionFeature::NEW_INITIALIZERS);

    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute(All::class),
        new AnnotationToAttribute(Length::class),
        new AnnotationToAttribute(NotNumber::class),
        new AnnotationToAttribute(GenericAnnotation::class),
    ]);

    $rectorConfig->ruleWithConfiguration(NestedAnnotationToAttributeRector::class, [
        new NestedAnnotationToAttribute('Doctrine\ORM\Mapping\Table', [
            new AnnotationPropertyToAttributeClass('Doctrine\ORM\Mapping\Index', 'indexes'),
            new AnnotationPropertyToAttributeClass('Doctrine\ORM\Mapping\UniqueConstraint', 'uniqueConstraints'),
        ]),
    ]);
};
