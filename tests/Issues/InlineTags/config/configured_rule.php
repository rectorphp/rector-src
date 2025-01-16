<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\AnnotationsToAttributes\Rector\Class_\CoversAnnotationWithValueToAttributeRector;

return RectorConfig::configure()
    ->withRules([CoversAnnotationWithValueToAttributeRector::class]);
