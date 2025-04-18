<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\AnnotationsToAttributes\Rector\Class_\AnnotationWithValueToAttributeRector;
use Rector\PHPUnit\ValueObject\AnnotationWithValueToAttribute;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AnnotationWithValueToAttributeRector::class, [
        new AnnotationWithValueToAttribute('testdox', 'PHPUnit\Framework\Attributes\TestDox'),
    ]);
};
