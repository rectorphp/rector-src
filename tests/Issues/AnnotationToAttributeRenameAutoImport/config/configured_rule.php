<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();

    $rectorConfig->ruleWithConfiguration(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted'),
    ]);

    $rectorConfig->ruleWithConfiguration(
        \Rector\Renaming\Rector\Name\RenameClassRector::class,
        [
            'Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted' => 'Symfony\Component\Security\Http\Attribute\IsGranted',
        ],
    );
};
