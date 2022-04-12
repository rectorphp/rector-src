<?php

declare(strict_types=1);

use Rector\TypeDeclaration\Rector\ClassMethod\AddArrayParamDocTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AddArrayParamDocTypeRector::class);
};
