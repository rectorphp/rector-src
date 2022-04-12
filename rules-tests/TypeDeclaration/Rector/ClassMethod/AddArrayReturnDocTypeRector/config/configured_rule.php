<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayReturnDocTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AddArrayReturnDocTypeRector::class);
};
