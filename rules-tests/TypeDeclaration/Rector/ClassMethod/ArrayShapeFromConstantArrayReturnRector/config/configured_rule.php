<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\ClassMethod\ArrayShapeFromConstantArrayReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ArrayShapeFromConstantArrayReturnRector::class);
};
