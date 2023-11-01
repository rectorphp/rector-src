<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();

    // simulate loaded different config
    $rectorConfig->ruleWithConfiguration(
        RenameClassRector::class,
        [
            'SomeClass' => 'SomeOtherClass',
        ],
    );

    // set annotation to attribute
    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted'),
    ]);

    // rename
    $rectorConfig->ruleWithConfiguration(
        RenameClassRector::class,
        [
            'Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted' => 'Symfony\Component\Security\Http\Attribute\IsGranted',
        ],
    );
};
