<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Property\NestedAnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationPropertyToAttributeClass;
use Rector\Php80\ValueObject\NestedAnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(NestedAnnotationToAttributeRector::class, [
        /** @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.13/reference/attributes-reference.html#joincolumn-inversejoincolumn */
        new NestedAnnotationToAttribute('Doctrine\ORM\Mapping\JoinTable', [
            new AnnotationPropertyToAttributeClass('Doctrine\ORM\Mapping\JoinColumn', 'joinColumns'),
            new AnnotationPropertyToAttributeClass(
                'Doctrine\ORM\Mapping\InverseJoinColumn',
                'inverseJoinColumns',
                true
            ),
        ]),

        new NestedAnnotationToAttribute('Doctrine\ORM\Mapping\Table', [
            new AnnotationPropertyToAttributeClass('Doctrine\ORM\Mapping\Index', 'indexes'),
            new AnnotationPropertyToAttributeClass('Doctrine\ORM\Mapping\UniqueConstraint', 'uniqueConstraints'),
        ]),

        /** @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html#joincolumns */
        new NestedAnnotationToAttribute('Doctrine\ORM\Mapping\JoinColumns', [
            new AnnotationPropertyToAttributeClass('Doctrine\ORM\Mapping\JoinColumn'),
        ], true),
    ]);
};
