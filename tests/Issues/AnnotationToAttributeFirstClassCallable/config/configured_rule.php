<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Php80\Rector\Class_\AnnotationToAttributeRector;
use Rector\Php80\ValueObject\AnnotationToAttribute;

return RectorConfig::configure()
    ->withConfiguredRule(AnnotationToAttributeRector::class, [
        new AnnotationToAttribute('Symfony\Component\Serializer\Annotation\Context'),
    ])
    ->withRules([
        FirstClassCallableRector::class,
    ]);
