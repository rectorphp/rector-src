<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::ATTRIBUTES);

    $rectorConfig
        ->ruleWithConfiguration(AnnotationToAttributeRector::class, [
            new AnnotationToAttribute('Doctrine\ORM\Mapping\Entity'),
        ]);

    // should merge with existing config
    $rectorConfig
        ->ruleWithConfiguration(AnnotationToAttributeRector::class, [
            new AnnotationToAttribute('Doctrine\ORM\Mapping\ManyToMany'),
        ]);
};
