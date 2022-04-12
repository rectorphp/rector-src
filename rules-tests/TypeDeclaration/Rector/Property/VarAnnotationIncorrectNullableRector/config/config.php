<?php

declare(strict_types=1);

namespace Utils\Rector\Tests\Rector\VarAnnotationMissingNullableRectorTest;

use Rector\TypeDeclaration\Rector\Property\VarAnnotationIncorrectNullableRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(VarAnnotationIncorrectNullableRector::class);
};
