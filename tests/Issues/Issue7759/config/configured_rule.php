<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnAnnotationIncorrectNullableRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ReturnAnnotationIncorrectNullableRector::class);
    $rectorConfig->rule(ArrayShapeFromConstantArrayReturnRector::class);
};
