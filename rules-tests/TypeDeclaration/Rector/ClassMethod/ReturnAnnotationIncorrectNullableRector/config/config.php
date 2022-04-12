<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnAnnotationIncorrectNullableRector;

use Rector\TypeDeclaration\Rector\ClassMethod\ReturnAnnotationIncorrectNullableRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnAnnotationIncorrectNullableRector::class);
};
