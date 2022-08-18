<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Property\NestedAnnotationToAttributeRector;
use Rector\Php80\ValueObject\NestedAnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(NestedAnnotationToAttributeRector::class, [
        new NestedAnnotationToAttribute('Doctrine\ORM\Mapping\JoinTable', [
            'joinColumns' => 'Doctrine\ORM\Mapping\JoinColumn',
            'inverseJoinColumns' => 'Doctrine\ORM\Mapping\InverseJoinColumn',
        ]),
    ]);
};
