<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Property\NestedAnnotationToAttributeRector;
use Rector\Php80\ValueObject\NestedAnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(NestedAnnotationToAttributeRector::class, [
        /** @see https://www.doctrine-project.org/projects/doctrine-orm/en/2.13/reference/attributes-reference.html#joincolumn-inversejoincolumn */
        new NestedAnnotationToAttribute('Doctrine\ORM\Mapping\JoinTable', [
            'joinColumns' => 'Doctrine\ORM\Mapping\JoinColumn',
            'inverseJoinColumns' => 'Doctrine\ORM\Mapping\InverseJoinColumn',
        ]),

        new NestedAnnotationToAttribute('Doctrine\ORM\Mapping\Table', [
            'indexes' => 'Doctrine\ORM\Mapping\Index',
            'uniqueConstraints' => 'Doctrine\ORM\Mapping\UniqueConstraint',
        ]),

        /** @see https://www.doctrine-project.org/projects/doctrine-orm/en/latest/reference/annotations-reference.html#joincolumns */
        new NestedAnnotationToAttribute('Doctrine\ORM\Mapping\JoinColumns', [
            'Doctrine\ORM\Mapping\JoinColumn',
        ], true),
    ]);
};
