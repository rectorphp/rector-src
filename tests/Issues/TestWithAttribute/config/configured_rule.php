<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\AnnotationsToAttributes\Rector\ClassMethod\TestWithAnnotationToAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TestWithAnnotationToAttributeRector::class);
};
